<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\Enums\WorkTypeEnum;
use App\Events\HourSessionRegistered;
use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class RegisterHourSessionService
{
    use ValidateTimeEntry;

    /**
     * @param  \App\Services\Salary\SalaryService  $salaryService
     */
    public function __construct(
        private HourWorkedEntryService $hourWorkedEntryService,
    ) {}

    /**
     * @throws \Exception
     */
    public function execute(?string $employeeId, string $date, string $startTime, string $endTime, int $plannedHours, ?string $workType): void
    {
        // Validaciones
        $this->validateDateIsToday($date);
        $this->validateTimeEntry($startTime, $endTime);

        // Verifica si ya existe una sesión de trabajo para el empleado en la misma fecha
        if ($this->sessionExists($employeeId, $date)) {
            throw new HourSessionExistException;
        }

        $transaction = DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $workType) {
            // Crear la sesión de trabajo
            $hourSession = HourSession::create([
                'employee_id' => $employeeId,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'planned_hours' => $plannedHours,
                'work_type' => $workType ?? WorkTypeEnum::NORMAL->value,
            ]);

            // Ejecutar el servicio de HourWorkedEntry
            $this->hourWorkedEntryService->execute(
                $hourSession->id,
                $hourSession->start_time,
                $hourSession->end_time,
                $hourSession->planned_hours,
                $hourSession->work_type
            );

            // Disparar evento después de la transacción
            DB::afterCommit(function () use ($employeeId, $date) {
                event(new HourSessionRegistered($employeeId, $date));
            });

            return $hourSession;
        });
    }

    /**
     * Verificar si ya existe una sesión de horas para un empleado en una fecha específica
     */
    protected function sessionExists(string $employeeId, string $date): bool
    {
        return HourSession::where('employee_id', $employeeId)
            ->where('date', $date)
            ->exists();
    }
}

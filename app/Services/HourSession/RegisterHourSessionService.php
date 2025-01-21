<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Events\HourSessionRegistered;
use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Services\Salary\SalaryService;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class RegisterHourSessionService
{
    use ValidateTimeEntry;

    public function __construct(private HourWorkedEntryService $hourWorkedEntryService, private SalaryService $salaryService) {}

    /**
     * Summary of execute
     *
     * @throws \App\Exceptions\HourSessionExistException
     */
    public function execute(string $employeeId, HourSessionDTO $hourSessionDTO): void
    {
        // Validaciones
        $this->validateDateIsToday($hourSessionDTO->date);
        $this->validateTimeEntry($hourSessionDTO->startTime, $hourSessionDTO->endTime);

        // Verifica si ya existe una sesión de trabajo para el empleado en la misma fecha
        if ($this->sessionExists($employeeId, $hourSessionDTO->date)) {
            throw new HourSessionExistException;
        }

        // Crear la sesión de trabajo
        $this->createHourSession($hourSessionDTO, $employeeId);

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

    /**
     * Summary of createHourSession
     */
    private function createHourSession(HourSessionDTO $hourSessionDTO, string $employeeId): void
    {
        DB::transaction(function () use ($employeeId, $hourSessionDTO): void {
            $hourSession = HourSession::create([
                'employee_id' => $employeeId,
                'date' => $hourSessionDTO->date,
                'start_time' => $hourSessionDTO->startTime,
                'end_time' => $hourSessionDTO->endTime,
                'planned_hours' => $hourSessionDTO->plannedHours,
                'work_type' => $hourSessionDTO->workType,
            ]);
            $this->hourWorkedEntryService->execute(
                $hourSession->id,
                $hourSession->start_time,
                $hourSession->end_time,
                $hourSession->planned_hours,
                $hourSession->work_type);

            DB::afterCommit(function () use ($employeeId, $hourSession) {

                event(new HourSessionRegistered($employeeId, $hourSession->date));

            });

        });
    }
}

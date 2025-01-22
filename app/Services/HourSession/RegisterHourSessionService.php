<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Events\RegisteredHourSessionEvent;
use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourWorked\CalculateTrait;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class RegisterHourSessionService
{
    use ValidateTimeEntry, CalculateTrait;

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
        $this->verifyDuration($hourSessionDTO->startTime, $hourSessionDTO->endTime);


        // Verifica si ya existe una sesión de trabajo para el empleado en la misma fecha
        if ($this->sessionExists($employeeId, $hourSessionDTO->date)) {
            throw new HourSessionExistException;
        }
        // Crear la sesión de trabajo
        $this->createHourSession($hourSessionDTO, $employeeId);

    }

    /**
     * Summary of sessionExists
     * @param string $employeeId
     * @param string $date
     * @return bool
     */
    protected function sessionExists(string $employeeId, string $date): bool
    {
        return HourSession::where('employee_id', $employeeId)
            ->where('date', $date)
            ->exists();
    }

    /**
     * Summary of createHourSession
     * @param HourSessionDTO $hourSessionDTO
     * @param string $employeeId
     * @return void
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
           
            DB::afterCommit(function () use ( $hourSession) {
                
                event(new RegisteredHourSessionEvent( $hourSession));
            });
        });
    }
}

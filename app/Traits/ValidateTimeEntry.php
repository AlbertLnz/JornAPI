<?php

declare(strict_types=1);

namespace App\Traits;

use App\Exceptions\TimeEntryException;
use App\Exceptions\TodayDateException;
use Carbon\Carbon;

trait ValidateTimeEntry
{
    
    /**
     * Summary of validateTimeEntry
     *
     * @param  mixed  $startTime
     * @param  mixed  $endTime
     *
     * @throws \App\Exceptions\TimeEntryException
     */
    public function validateTimeEntry(?string $startTime, ?string $endTime): void
    {
        if ($startTime == null || $endTime == null) {
            throw new TimeEntryException("'The start time and end time are required'");
        }
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        // Si el tiempo de fin es menor o igual al de inicio, asumimos que es del día siguiente
        if ($end <= $start) {
            // Añadir un día a la hora de fin
            $end->addDay();
        }

        // Validación: si después de ajustar sigue siendo inválido, lanzamos excepción
        if ($start > $end) {
            throw new TimeEntryException("'The start time cannot be greater than the end time'");
        }

        $this->verifyDuration($start, $end);
        
    }

    /**
     * Summary of validateDateIsToday
     *
     * @throws \App\Exceptions\TodayDateException
     */
    public function validateDateIsToday(string $date): void
    {
        $today = Carbon::today();
        $date = Carbon::parse($date);

        if ($date > $today) {
            throw new TodayDateException;
        }
    }

    private function verifyDuration($start, $end): mixed
    {
        $maxHoursWorked = 12;
        $minHoursWorked = 2;
        if ($end < $start) {
            // Añadir un día a la hora de fin
            throw new TimeEntryException('The start time cannot be greater than the end time');
        }

        if ($end <= $start || $start > $end) {
            throw new TimeEntryException('The start time cannot be greater than the end time');
        }
        $hoursWorkedCalculated = $this->diffInHours($start, $end);

        if ($hoursWorkedCalculated >= $maxHoursWorked || $hoursWorkedCalculated < $minHoursWorked) {
            throw new TimeEntryException(
                "The hours worked must be between {$minHoursWorked} and {$maxHoursWorked}. You provided {$hoursWorkedCalculated}."
            );
        }

        return $hoursWorkedCalculated;
    }
}

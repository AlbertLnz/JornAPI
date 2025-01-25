<?php

declare(strict_types=1);

namespace App\Services\HourWorked;

use App\Exceptions\TimeEntryException;
use Carbon\Carbon;

trait CalculateTrait
{
    use HourCalculateTrait;

    /**
     * Summary of calculate
     *
     * @param  mixed  $startTime
     * @param  mixed  $endTime
     * @param  mixed  $plannedHours
     * @param  mixed  $isHoliday
     * @param  mixed  $isOvertime
     */
    private function calculate($startTime, $endTime, $plannedHours, ?string $workType): array
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $hoursWorkedCalculated = $this->diffInHours($start, $end);
        // Verificar si es festivo y calcular las horas festivas
        $holidayHours = $this->calculateHolidayHours($hoursWorkedCalculated, $workType);
        // Calcular horas extras
        $regularOvertimeHours = $this->calculateRegularOvertimeHours($hoursWorkedCalculated, $plannedHours, $workType);

        // Calcular dia complementario
        $extraShiftHours = $this->calculateExtraShiftOvertime($hoursWorkedCalculated, $workType);
        // Calcular las horas normales
        $normalHours = $this->calculateNormalHours(
            $hoursWorkedCalculated,
            $regularOvertimeHours,
            $workType);

        return [
            'normalHours' => $normalHours,
            'overtimeHours' => $regularOvertimeHours + $extraShiftHours,
            'holidayHours' => $holidayHours,
        ];
    }

    private function diffInHours($start, $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        return (float) $start->floatDiffInHours($end);
    }

   
  
}

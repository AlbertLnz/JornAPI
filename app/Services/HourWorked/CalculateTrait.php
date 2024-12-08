<?php
declare(strict_types=1);

namespace App\Services\HourWorked;

use Carbon\Carbon;

trait CalculateTrait{

    use HourCalculateTrait;
    /**
     * Summary of calculate
     * @param mixed $startTime
     * @param mixed $endTime
     * @param mixed $plannedHours
     * @param mixed $isHoliday
     * @param mixed $isOvertime
     * @return array
     */
    private function calculate( $startTime, $endTime, $plannedHours, ?string $workType): array{
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
  
        // Calcular las horas trabajadasF
        $hoursWorkedCalculated = $start->floatDiffInHours($end);
        // Calcular horas extras
        $regularOvertimeHours = $this->calculateRegularOvertimeHours($hoursWorkedCalculated, $plannedHours, $workType);
        // Verificar si es festivo y calcular las horas festivas
        $holidayHours = $this->calculateHolidayHours($hoursWorkedCalculated, $regularOvertimeHours, $workType);

        $extraShiftHours = $this->calculateExtraShiftOvertime($hoursWorkedCalculated, $workType);
        // Calcular las horas normales
        $normalHours = $this->calculateNormalHours(
          $hoursWorkedCalculated, 
          $regularOvertimeHours, 
          $workType);

  
        return[
          'normalHours' => $normalHours,
          'overtimeHours' => $regularOvertimeHours + $extraShiftHours,
          'holidayHours' => $holidayHours
        ];
    }
}
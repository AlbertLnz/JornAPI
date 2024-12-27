<?php

declare(strict_types=1);

namespace App\Services\HourWorked;

use App\Enums\WorkTypeEnum;

trait HourCalculateTrait
{
    /**
     * Calcular las horas extras.
     * Las horas extras son las horas trabajadas
     */
    private function calculateRegularOvertimeHours(float $hoursWorked, float $plannedHours, $workType): float
    {
        if (WorkTypeEnum::OVERTIME->value === $workType || WorkTypeEnum::HOLIDAY->value === $workType) {
            return 0;
        }

        if ($hoursWorked <= $plannedHours) {
            return 0;
        }

        $overtimeHours = $hoursWorked - $plannedHours;

        return $overtimeHours;
    }

    /**
     * Calcular las horas festivas.
     */
    private function calculateHolidayHours(float $hoursWorked, float $overtimeHours, $workType): float
    {
        if (WorkTypeEnum::HOLIDAY->value === $workType) {
            return $hoursWorked;
        }

        return 0;
    }

    /**
     * Calcular las horas normales.
     * Las horas normales son las horas trabajadas menos las extras, nocturnas y festivas.
     */
    private function calculateNormalHours(float $hoursWorked, float $overtimeHours, $workType): float
    {
        if (WorkTypeEnum::HOLIDAY->value === $workType || WorkTypeEnum::OVERTIME->value === $workType) {

            return 0;
        }

        return $hoursWorked - $overtimeHours;
    }

    private function calculateExtraShiftOvertime(float $hoursWorked, $workType): float
    {
        if (WorkTypeEnum::OVERTIME->value === $workType) {
            return $hoursWorked;
        }

        return 0;
    }
}

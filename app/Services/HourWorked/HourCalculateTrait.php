<?php

declare(strict_types=1);

namespace App\Services\HourWorked;

use App\Enums\WorkTypeEnum;

trait HourCalculateTrait
{
    /**
     * Summary of calculateRegularOvertimeHours
     */
    private function calculateRegularOvertimeHours(float $hoursWorked, float $plannedHours, string $workType): float
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
     * Summary of calculateHolidayHours
     */
    private function calculateHolidayHours(float $hoursWorked, string $workType): float
    {
        if (WorkTypeEnum::HOLIDAY->value === $workType) {
            return $hoursWorked;
        }

        return 0;
    }

    /**
     * Summary of calculateNormalHours
     */
    private function calculateNormalHours(float $hoursWorked, float $overtimeHours, string $workType): float
    {
        if (WorkTypeEnum::HOLIDAY->value === $workType || WorkTypeEnum::OVERTIME->value === $workType) {

            return 0;
        }

        return $hoursWorked - $overtimeHours;
    }

    /**
     * Summary of calculateExtraShiftOvertime
     */
    private function calculateExtraShiftOvertime(float $hoursWorked, string $workType): float
    {
        if (WorkTypeEnum::OVERTIME->value === $workType) {
            return $hoursWorked;
        }

        return 0;
    }
}

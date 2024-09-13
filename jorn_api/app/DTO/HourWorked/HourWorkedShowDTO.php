<?php 

namespace App\DTO\HourWorked;

use App\Models\HourWorked;

class HourWorkedShowDTO
{
    public function __construct(
        public string $employeeId,
        public string $date,
        public string $startTime,
        public string $endTime,
        public int $plannedHours,
        public bool $isHoliday,
        public bool $isOvertime

    ){}

    public static function fromHourWorked(HourWorked $hourWorked): self
    {
        return new self(
            $hourWorked->employee_id,
            $hourWorked->date,
            $hourWorked->start_time,
            $hourWorked->end_time,
            $hourWorked->planned_hours,
            $hourWorked->is_holiday,
            $hourWorked->is_overtime
        );
    }
}
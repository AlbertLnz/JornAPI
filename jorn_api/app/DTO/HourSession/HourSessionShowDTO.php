<?php 

namespace App\DTO\HourSession;

use App\Models\HourSession;

class HourSessionShowDTO
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

    public static function fromHourSession(HourSession $HourSession): self
    {
        return new self(
            $HourSession->employee_id,
            $HourSession->date,
            $HourSession->start_time,
            $HourSession->end_time,
            $HourSession->planned_hours,
            $HourSession->is_holiday,
            $HourSession->is_overtime
        );
    }
}
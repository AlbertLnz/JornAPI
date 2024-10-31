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

    public static function fromHourSession(HourSession $hourSession): self
    {
        return new self(
            $hourSession->employee_id,
            $hourSession->date,
            $hourSession->start_time,
            $hourSession->end_time,
            $hourSession->planned_hours,
            $hourSession->is_holiday,
            $hourSession->is_overtime
        );
    }
}
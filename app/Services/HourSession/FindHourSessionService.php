<?php

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;

class FindHourSessionService
{
    public function __construct() {}

    /**
     * Summary of execute
     *
     * @return \App\DTO\HourSession\HourSessionDTO
     *
     * @throws \App\Exceptions\HourSessionNotFoundException
     */
    public function execute(string $employeeId, string $date): array
    {
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->select('date', 'start_time', 'end_time', 'planned_hours', 'work_type')->first();
        if (! $hourSession) {
            throw new HourSessionNotFoundException;
        }

        return HourSessionDTO::toArray($hourSession->toArray());
    }
}

<?php 

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HourSessionShowService{
    public function __construct(){}
    /**
     * Summary of execute
     * @param string $employeeId
     * @param string $date
     * @throws \App\Exceptions\HourSessionNotFoundException
     * @return \App\DTO\HourSession\HourSessionDTO
     */
    public function execute(string $employeeId, string $date): array
    {
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->select( 'date', 'start_time', 'end_time', 'planned_hours', 'work_type')->first();
        if(!$hourSession){
            throw new HourSessionNotFoundException();
        }
        return HourSessionDTO::toArray($hourSession->toArray());
    }
}
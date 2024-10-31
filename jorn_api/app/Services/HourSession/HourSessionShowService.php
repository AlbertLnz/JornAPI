<?php 

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionShowDTO;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HourSessionShowService{
    public function __construct(){}

    public function execute(string $employeeId, string $date): HourSessionShowDTO
    {
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->select( 'date', 'start_time', 'end_time', 'planned_hours', 'is_holiday', 'is_overtime')->first();
        if(!$hourSession){
            throw new HourSessionNotFoundException();
        }
        return HourSessionShowDTO::fromHourSession($hourSession);
    }
}
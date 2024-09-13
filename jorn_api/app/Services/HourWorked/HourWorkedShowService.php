<?php 

namespace App\Services\HourWorked;

use App\DTO\HourWorked\HourWorkedShowDTO;
use App\Exceptions\HourWorkedNotFoundException;
use App\Models\HourWorked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HourWorkedShowService{
    public function __construct(){}

    public function execute(string $employeeId, string $date): HourWorkedShowDTO
    {
        $hourWorked = HourWorked::where('employee_id', $employeeId)->where('date', $date)->first();
        if(!$hourWorked){
            throw new HourWorkedNotFoundException();
        }
        return HourWorkedShowDTO::fromHourWorked($hourWorked);
    }
}
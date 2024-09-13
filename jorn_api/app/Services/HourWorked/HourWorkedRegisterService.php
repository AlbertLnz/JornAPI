<?php 

namespace App\Services\HourWorked;

use App\Exceptions\HourWorkedExistException;
use App\Exceptions\HourWorkedNotFoundException;
use App\Exceptions\TimeEntryException;
use App\Models\HourWorked;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourWorkedRegisterService{
use ValidateTimeEntry;
    public function __construct(){}

    public function execute(string $employeeId, string $date, string $startTime, string $endTime, int $plannedHours, bool $isHoliday, bool $isOvertime): void
    {
        $findHourWorked =  HourWorked::where('date', $date)->first();
        if($findHourWorked){
            throw new HourWorkedExistException();
        }
     
       $transaction = DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime) {
        $this->validateTimeEntry($startTime, $endTime);

            $hourWorked = HourWorked::create([
                'employee_id' => $employeeId,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'planned_hours' => $plannedHours,
                'is_holiday' => $isHoliday,
                'is_overtime' => $isOvertime
            ]);
            return $hourWorked;
        });
        if(!$transaction){
            throw new \Exception("Failed to register hour worked");
        }
     
    }

  
}

<?php
namespace App\Services\HourWorked;

use App\DTO\HourWorked\HourWorkedShowDTO;
use App\Exceptions\HourWorkedNotFoundException;
use App\Models\HourWorked;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourWorkedUpdateService{
    use ValidateTimeEntry;

    public function execute(?string $employeeId, ?string $date, ?string $startTime, ?string $endTime, ?int $plannedHours, ?bool $isHoliday, ?bool $isOvertime): HourWorkedShowDTO
    {
        $hourWorked = HourWorked::where('employee_id', $employeeId)->where('date', $date)->first();
        if(!$hourWorked){
            
            throw new HourWorkedNotFoundException();
        }


         DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime, $hourWorked) {
        $this->validateTimeEntry($startTime, $endTime);

        if($date != null){
            $hourWorked->date = $date;
        }
        if($startTime != null){
            $hourWorked->start_time = $startTime;
        }
        if($endTime != null){
            $hourWorked->end_time = $endTime;
        }
        if($plannedHours != null){
            $hourWorked->planned_hours = $plannedHours;
        }
        if($isHoliday != null){
            $hourWorked->is_holiday = $isHoliday;
        }
        if($isOvertime != null){
            $hourWorked->is_overtime = $isOvertime;
        }
        if($employeeId != null){
            $hourWorked->employee_id = $employeeId;
        }
        $hourWorked->save();
        });
    

        return HourWorkedShowDTO::fromHourWorked($hourWorked);
    }
}
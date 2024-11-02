<?php 
declare(strict_types=1);
namespace App\Services\HourWorked;

use App\Exceptions\HourWorkedNotFoundException;
use App\Models\HourWorked;
use App\Traits\ValidateTimeEntry;
use Carbon\Carbon;

class HourWorkedUpdateService
{
    use CalculateTrait;
    use ValidateTimeEntry;
    /**
     * Summary of execute
     * @param string $hourSessionId
     * @param mixed $startTime
     * @param mixed $endTime
     * @param mixed $plannedHours
     * @param mixed $isHoliday
     * @param mixed $isOvertime
     * @throws \App\Exceptions\HourWorkedNotFoundException
     * @return void
     */
    public function execute( string $hourSessionId, $startTime, $endTime, $plannedHours, $workType): void
    {
        $this->validateTimeEntry($startTime, $endTime);

        $hourWorkeed = HourWorked::find($hourSessionId); 
        if(!$hourWorkeed){
            throw new HourWorkedNotFoundException();
        }

$hoursList =  $this->calculate($startTime, $endTime, $plannedHours, $workType);  
    
        $hourWorkeed->update([
         'total_normal_hours' =>  $hoursList['normalHours'], // 
            'total_overtime_hours' =>  $hoursList['overtimeHours'],
            'total_holiday_hours' => $hoursList['holidayHours'],
       ]);
    }

   
}
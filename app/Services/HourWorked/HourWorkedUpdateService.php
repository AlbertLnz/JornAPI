<?php 
declare(strict_types=1);
namespace App\Services\HourWorked;

use App\Exceptions\HourWorkedNotFoundException;
use App\Models\HourWorked;
use App\Traits\ValidateTimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function execute( string $hourSessionId, ?string $startTime, ?string $endTime, ?int $plannedHours, $workType): void
    {

        $hourWorkeed = HourWorked::where('hour_session_id', $hourSessionId)->first(); 
        if(!$hourWorkeed){
            throw new HourWorkedNotFoundException();
        }
       
       
        $this->validateTimeEntry($startTime, $endTime);


$hoursList =  $this->calculate($startTime, $endTime, $plannedHours, $workType);  
    
DB::transaction(function () use ($hourWorkeed, $hoursList) {
    if($hourWorkeed->normal_hours != null){
        $hourWorkeed->normal_hours = $hoursList['normalHours'];
    }
    if($hourWorkeed->overtime_hours != null){
        $hourWorkeed->overtime_hours = $hoursList['overtimeHours'];
    }
    if($hourWorkeed->holiday_hours != null){
        $hourWorkeed->holiday_hours = $hoursList['holidayHours'];
    }
    $hourWorkeed->save();
});
       
    }

   
}
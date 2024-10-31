<?php
namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionShowDTO;
use App\Events\HourSessionUpdatedEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Services\HourWorked\HourWorkedUpdateService;
use App\Traits\ValidateTimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HourSessionUpdateService{
    use ValidateTimeEntry;

    public function __construct(private HourWorkedUpdateService $hourWorkedEntryService){}

    public function execute(?string $employeeId, ?string $date, ?string $startTime, ?string $endTime, ?int $plannedHours, ?bool $isHoliday, ?bool $isOvertime): HourSessionShowDTO
    {
       // $this->validateDateIsToday($date);
        $carbon = new Carbon($date);
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $carbon->format('Y-m-d') )->first();
        if(!$hourSession){
            
            throw new HourSessionNotFoundException();
        }

         DB::transaction(function () use ($employeeId, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime, $hourSession) {
        $this->validateTimeEntry($startTime, $endTime);

       
        if($startTime != null){
            $hourSession->start_time = $startTime;
        }
        if($endTime != null){
            $hourSession->end_time = $endTime;
        }
        if($plannedHours != null){
            $hourSession->planned_hours = $plannedHours;
        }
        if($isHoliday != null){
            $hourSession->is_holiday = $isHoliday;
        }
        if($isOvertime != null){
            $hourSession->is_overtime = $isOvertime;
        }
        if($employeeId != null){
            $hourSession->employee_id = $employeeId;
        }
        $hourSession->save();

        $this->hourWorkedEntryService->execute($hourSession->id, $hourSession->start_time, $hourSession->end_time, $hourSession->planned_hours, $hourSession->is_holiday, $hourSession->is_overtime);
  
        });

        event(new HourSessionUpdatedEvent( $employeeId, $date));
    

        return HourSessionShowDTO::fromHourSession($hourSession);
    }
}
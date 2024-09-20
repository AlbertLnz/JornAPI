<?php
namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionShowDTO;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourSessionUpdateService{
    use ValidateTimeEntry;

    public function __construct(private HourWorkedEntryService $hourWorkedEntryService){}

    public function execute(?string $employeeId, ?string $date, ?string $startTime, ?string $endTime, ?int $plannedHours, ?bool $isHoliday, ?bool $isOvertime): HourSessionShowDTO
    {
        $HourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->first();
        if(!$HourSession){
            
            throw new HourSessionNotFoundException();
        }


         DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime, $HourSession) {
        $this->validateTimeEntry($startTime, $endTime);

        if($date != null){
            $HourSession->date = $date;
        }
        if($startTime != null){
            $HourSession->start_time = $startTime;
        }
        if($endTime != null){
            $HourSession->end_time = $endTime;
        }
        if($plannedHours != null){
            $HourSession->planned_hours = $plannedHours;
        }
        if($isHoliday != null){
            $HourSession->is_holiday = $isHoliday;
        }
        if($isOvertime != null){
            $HourSession->is_overtime = $isOvertime;
        }
        if($employeeId != null){
            $HourSession->employee_id = $employeeId;
        }
        $HourSession->save();
        $this->hourWorkedEntryService->execute(
            $HourSession->employee_id,
            $HourSession->start_time,
            $HourSession->end_time,
            $HourSession->planned_hours, 
            $HourSession->is_holiday, 
            $HourSession->is_overtime
        );
        });
    

        return HourSessionShowDTO::fromHourSession($HourSession);
    }
}
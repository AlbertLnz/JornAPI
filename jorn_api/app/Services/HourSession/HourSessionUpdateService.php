<?php
declare(strict_types=1);
namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionShowDTO;
use App\Events\HourSessionUpdatedEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedUpdateService;
use App\Traits\ValidateTimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HourSessionUpdateService{
    use ValidateTimeEntry;
    /**
     * Summary of __construct
     * @param \App\Services\HourWorked\HourWorkedUpdateService $hourWorkedUpdateService
     */
    public function __construct(private HourWorkedUpdateService $hourWorkedUpdateService){}
    /**
     * Summary of execute
     * @param mixed $employeeId
     * @param mixed $date
     * @param mixed $startTime
     * @param mixed $endTime
     * @param mixed $plannedHours
     * @param mixed $isHoliday
     * @param mixed $isOvertime
     * @throws \App\Exceptions\HourSessionNotFoundException
     * @return \App\DTO\HourSession\HourSessionShowDTO
     */
    public function execute(?string $employeeId, ?string $date, ?string $startTime, ?string $endTime, ?int $plannedHours, $workType): HourSessionShowDTO
    {
       // $this->validateDateIsToday($date);
        $carbon = new Carbon($date);
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $carbon->format('Y-m-d') )->first();
        if(!$hourSession){
            
            throw new HourSessionNotFoundException();
        }
        $this->validateTimeEntry($startTime, $endTime);

         DB::transaction(function () use ($employeeId, $startTime, $endTime, $plannedHours, $workType, $hourSession) {

       
        if($startTime != null){
            $hourSession->start_time = $startTime;
        }
        if($endTime != null){
            $hourSession->end_time = $endTime;
        }
        if($plannedHours != null){
            $hourSession->planned_hours = $plannedHours;
        }
        if($workType != null){
            $hourSession->work_type = $workType;
        }
        if($employeeId != null){
            $hourSession->employee_id = $employeeId;
        }
        $hourSession->save();

        $this->hourWorkedUpdateService->execute($hourSession->id, $hourSession->start_time, $hourSession->end_time, $hourSession->planned_hours, $workType);
  
        });

        event(new HourSessionUpdatedEvent( $employeeId, $date));
    

        return HourSessionShowDTO::fromHourSession($hourSession);
    }
}
<?php 
declare(strict_types=1);
namespace App\Services\HourSession;

use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourSessionRegisterService{
use ValidateTimeEntry;
    public function __construct(private HourWorkedEntryService $hourWorkedEntryService){}

    public function execute(string $employeeId, string $date, string $startTime, string $endTime, int $plannedHours, bool $isHoliday, bool $isOvertime): void
    {
        $findHourSession =  HourSession::where('date', $date)->first();
        if($findHourSession){
            throw new HourSessionExistException();
        }
      // $this->validateDateIsToday($date);
       $this->validateTimeEntry($startTime, $endTime);

     
       $transaction = DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime) {
            $HourSession = HourSession::create([
                'employee_id' => $employeeId,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'planned_hours' => $plannedHours,
                'is_holiday' => $isHoliday,
                'is_overtime' => $isOvertime
            ]);
            $this->hourWorkedEntryService->execute(
             $HourSession->id,
             $HourSession->start_time,
             $HourSession->end_time,
             $HourSession->planned_hours, 
             $HourSession->is_holiday, 
             $HourSession->is_overtime);
            return $HourSession;
        });
        if(!$transaction){
            throw new \Exception("Failed to register hour worked");
        }
     
    }

  
}

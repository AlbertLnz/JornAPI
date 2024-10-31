<?php 
declare(strict_types=1);
namespace App\Services\HourSession;

use App\Events\HourSessionRegistered;
use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Services\Salary\SalaryService;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourSessionRegisterService{
use ValidateTimeEntry;
    public function __construct(private HourWorkedEntryService $hourWorkedEntryService , private SalaryService $salaryService){}

    public function execute(string $employeeId, string $date, string $startTime, string $endTime, int $plannedHours, bool $isHoliday, bool $isOvertime): void
    {
        $findHourSession =  HourSession::where('date', $date)->first();
        if($findHourSession){
            throw new HourSessionExistException();
        }
       $this->validateDateIsToday($date);
       $this->validateTimeEntry($startTime, $endTime);

     
       $transaction = DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime) {
            $hourSession = HourSession::create([
                'employee_id' => $employeeId,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'planned_hours' => $plannedHours,
                'is_holiday' => $isHoliday,
                'is_overtime' => $isOvertime
            ]);
         

       //$this->salaryService->execute($employeeId, $date);

            return $hourSession;
        });

        if(!$transaction){
            throw new \Exception("Failed to register hour worked");
        }
        $this->hourWorkedEntryService->execute(
            $transaction->id,
            $transaction->start_time,
            $transaction->end_time,
            $transaction->planned_hours, 
            $transaction->is_holiday, 
            $transaction->is_overtime); 
      event(new HourSessionRegistered($transaction, $employeeId, $date));

     
    }

  
}

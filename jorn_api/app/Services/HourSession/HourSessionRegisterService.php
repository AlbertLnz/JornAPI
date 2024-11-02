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
    /**
     * Summary of __construct
     * @param \App\Services\HourWorked\HourWorkedEntryService $hourWorkedEntryService
     * @param \App\Services\Salary\SalaryService $salaryService
     */
    public function __construct(private HourWorkedEntryService $hourWorkedEntryService , private SalaryService $salaryService){}
    /**
     * Summary of execute
     * @param string $employeeId
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @param int $plannedHours
     * @param bool $isHoliday
     * @param bool $isOvertime
     * @throws \Exception
     * @return void
     */
    public function execute(?string $employeeId, string $date, string $startTime, string $endTime, int $plannedHours, ?string $workType): void
    {
     
       $this->validateDateIsToday($date);
       $this->validateTimeEntry($startTime, $endTime);

     
       $transaction = DB::transaction(function () use ($employeeId, $date, $startTime, $endTime, $plannedHours, $workType) {
            $hourSession = HourSession::create([
                'employee_id' => $employeeId,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'planned_hours' => $plannedHours,
                'work_type' => $workType ?? null
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
           $transaction->work_type); 
      event(new HourSessionRegistered( $employeeId, $date));

     
    }

  
}

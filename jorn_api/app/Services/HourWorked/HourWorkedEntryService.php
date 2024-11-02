<?php
declare(strict_types=1);
namespace App\Services\HourWorked;


use App\Models\HourWorked;
use App\Services\Salary\SalaryService;
use Carbon\Carbon;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourWorkedEntryService
{
    use ValidateTimeEntry;
    use CalculateTrait;
    /**
     * Summary of __construct
     * @param \App\Services\Salary\SalaryService $salaryService
     */
    public function __construct( private SalaryService $salaryService ) {}
    /**
     * Summary of execute
     * @param string $hourSessionId
     * @param mixed $startTime
     * @param mixed $endTime
     * @param mixed $plannedHours
     * @param mixed $isHoliday
     * @param mixed $isOvertime
     * @return void
     */
    public function execute(string $hourSessionId, $startTime, $endTime, $plannedHours,  $workType): void
    {
        // Validar la entrada de tiempo
        $this->validateTimeEntry($startTime, $endTime);

       $hoursList = $this->calculate($startTime, $endTime, $plannedHours, $workType );

        DB::transaction(function () use ($hourSessionId, $hoursList)
        {
            HourWorked::create([
                'hour_session_id' => $hourSessionId,
                'normal_hours' => $hoursList['normalHours'],
                'overtime_hours' => $hoursList['overtimeHours'],
                'holiday_hours' => $hoursList['holidayHours'],
            ]);
        });
        

        
          

    }

     
    

 
}

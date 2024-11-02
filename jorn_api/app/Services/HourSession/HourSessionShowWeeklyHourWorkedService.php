<?php 
namespace App\Services\HourSession;
use App\Models\HourSession;
use Carbon\Carbon;
use App\Models\HourWorked;
use App\Models\Employee;

class HourSessionShowWeeklyHourWorkedService
{
    /**
     * Summary of execute
     * @param string $employeeId
     * @return array
     */
    public function execute(string $employeeId): array
    {
        $startDate = Carbon::now()->startOfWeek()->toDateString(); 
        $endDate = Carbon::now()->endOfWeek()->toDateString();
        $hourSessions = HourSession::where('employee_id', $employeeId)
        ->whereBetween('date', [$startDate, $endDate])
        ->select('total_normal_hours', 'total_overtime_hours', 'total_holiday_hours')->get();

        $totalNormalHours = $hourSessions->sum('total_normal_hours');
        $totalOvertimeHours = $hourSessions->sum('total_overtime_hours');
        $totalHolidayHours = $hourSessions->sum('total_holiday_hours');
       
        $totalHours = $totalNormalHours + $totalOvertimeHours + $totalHolidayHours ;
        
        $totalHoursWorked = $totalNormalHours + $totalOvertimeHours + $totalHolidayHours ;
       
        return [
            
            'total_hours' => $totalHours
           
        ];
    }
}
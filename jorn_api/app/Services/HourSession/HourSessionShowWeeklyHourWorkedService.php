<?php 
namespace App\Services\HourSession;
use App\Models\HourSession;
use Carbon\Carbon;
use App\Models\HourWorked;
use App\Models\Employee;

class HourSessionShowWeeklyHourWorkedService
{
    public function execute(string $employeeId)
    {
        $startDate = Carbon::now()->startOfWeek()->toDateString(); 
        var_dump("start",$$startDate);// Lunes
        $endDate = Carbon::now()->endOfWeek()->toDateString();
        $hourSessions = HourSession::where('employee_id', $employeeId)->whereBetween('date', [$startDate, $endDate])->get();

        $totalNormalHours = $hourSessions->sum('total_normal_hours');
        $totalOvertimeHours = $hourSessions->sum('total_overtime_hours');
        $totalHolidayHours = $hourSessions->sum('total_holiday_hours');
        $totalNightHours = $hourSessions->sum('total_night_hours');
        $totalHours = $totalNormalHours + $totalOvertimeHours + $totalHolidayHours + $totalNightHours;
        
        $totalHoursWorked = $totalNormalHours + $totalOvertimeHours + $totalHolidayHours + $totalNightHours;
       
        return [
            
            'total_hours' => $totalHours
           
        ];
    }
}
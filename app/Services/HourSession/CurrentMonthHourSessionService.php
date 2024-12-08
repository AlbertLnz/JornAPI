<?php 
declare(strict_types=1);
namespace App\Services\HourSession;
use App\Models\HourSession;
use Illuminate\Database\Eloquent\Collection;

class CurrentMonthHourSessionService
{
    /**
     * Summary of execute
     * @param string $employeeId
     * @return 
     */
    public function execute(string $employeeId, $startMonth, $endMonth): Collection
    {
      
        $hourSessions = HourSession::where('employee_id', $employeeId)
        ->whereBetween('date', [$startMonth, $endMonth])->select( 'date', 'planned_hours', 'start_time', 'end_time', 'work_type')->get();

        return $hourSessions;
    }
}
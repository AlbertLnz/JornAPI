<?php 
declare(strict_types=1);
namespace App\Services\Dashboard;

use App\Exceptions\SalaryNotFoundException;
use App\Models\User;
use App\Services\HourSession\CurrentMonthHourSessionService;
use App\Services\Salary\FindSalaryByMonthService;
use Carbon\Carbon;

class DashboardService{
  
    public function __construct(private FindSalaryByMonthService $findSalaryByMonthService,private CurrentMonthHourSessionService $currentMonthHourSessionService ){}


    public function execute(User $user): array{

        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $startMonth = Carbon::create($year, (int)$month, 1);
        $endMonth = Carbon::create($year, $month + 1, 0);
        
try{
    
    $currentMonthSalary = $this->findSalaryByMonthService->execute($user->employee->id, $startMonth->format('m'), $startMonth->format('Y'));
    $currentMonthHourSession = $this->currentMonthHourSessionService->execute($user->employee->id, $startMonth, $endMonth);

    $totalHoursWorked = $currentMonthSalary->total_normal_hours + $currentMonthSalary->total_overtime_hours + $currentMonthSalary->total_holiday_hours;
    return [
        'total_hours_worked' => $totalHoursWorked,
        'current_month_salary' => $currentMonthSalary->total_gross_salary,
        'current_month_hours_session' => $currentMonthHourSession
    ];
}catch(SalaryNotFoundException $e){
    
    return [
        'total_hours_worked' => 0,
        'current_month_salary' => 0,
        'current_month_hours_session' => []
    ];
}

     
    }
}
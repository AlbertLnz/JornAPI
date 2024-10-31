<?php
namespace App\Services\Salary;

use App\DTO\Salary\ShowSalaryDTO;
use App\Models\Salary;
use Carbon\Carbon;

class FindSalaryByMonthService{


    public function execute(string $employeeId, string $month, string $year){
        $str = $year."-".$month."-01";
        $date = new Carbon($str);
        $salary = Salary::where('employee_id', $employeeId)->whereMonth('start_date', $month)->whereYear('start_date', $year)->select( 'start_date', 'end_date', 'total_normal_hours', 'total_overtime_hours', 'total_night_hours', 'total_holiday_hours', 'total_gross_salary', 'total_net_salary')->first();
    
       
        return ShowSalaryDTO::fromSalary($salary);
    }
}

<?php
declare(strict_types=1);
namespace App\Services\Salary;

use App\Models\Employee;

trait CalculateSalaryTrait
{
    public function calculateSalary(mixed $hoursWorkeds, Employee $employee): array
    {
     $hourCalculations = $this->calculateTotalHoursWorked($hoursWorkeds);
   
     $grossSalary =  $this->calculateGrossSalary(
        $hourCalculations['total_normal_hours'], 
        $hourCalculations['total_overtime_hours'], 
        $hourCalculations['total_holiday_hours'], 
        $employee);
        return [
            'total_normal_hours' => $hourCalculations['total_normal_hours'],
            'total_overtime_hours' => $hourCalculations['total_overtime_hours'],
            'total_holiday_hours' => $hourCalculations['total_holiday_hours'],
            'gross_salary' => $grossSalary,
      ];
    }

    private function calculateTotalHoursWorked(mixed $hoursWorkeds):array
    {
        $totalNormalHours = $hoursWorkeds->sum('total_normal_hours');
        $totalOvertimeHours = $hoursWorkeds->sum('total_overtime_hours');
        $totalHolidayHours = $hoursWorkeds->sum('total_holiday_hours');
     
        return[
            'total_normal_hours' => $totalNormalHours,
            'total_overtime_hours' => $totalOvertimeHours,
            'total_holiday_hours' => $totalHolidayHours,
           
        ];
    }

    private function calculateGrossSalary(float $totalNormalHours, float $totalOvertimeHours, float $totalHolidayHours, Employee $employee): float{
        $normal_hourly_rate= $employee->normal_hourly_rate;
        $overtime_hourly_rate= $employee->overtime_hourly_rate;
        $holiday_hourly_rate= $employee->holiday_hourly_rate;
        return $normal_hourly_rate * $totalNormalHours + $overtime_hourly_rate * $totalOvertimeHours + $holiday_hourly_rate * $totalHolidayHours ;

    }
}
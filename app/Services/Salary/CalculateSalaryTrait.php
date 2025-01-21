<?php

declare(strict_types=1);

namespace App\Services\Salary;

use App\Models\Employee;
use App\Traits\TimeConverterTrait;

trait CalculateSalaryTrait
{
    use TimeConverterTrait;

    /**
     * Summary of calculateSalary
     * @param mixed $hoursWorked
     * @param Employee $employee
     * @return array
     */
    public function calculateSalary(mixed $hoursWorked, Employee $employee): array
    {
        $hourCalculations = $this->calculateTotalHoursWorked($hoursWorked);
        $convertToNormalHours = $this->convertDecimalToHoursAndMinutes($hourCalculations['total_normal_hours']);
        $convertToOvertimeHours = $this->convertDecimalToHoursAndMinutes($hourCalculations['total_overtime_hours']);
        $convertToHolidayHours = $this->convertDecimalToHoursAndMinutes($hourCalculations['total_holiday_hours']);

        $grossSalary = $this->calculateGrossSalary(
            $convertToNormalHours,
            $convertToOvertimeHours,
            $convertToHolidayHours,
            $employee);

            $netSalary = $this->calculateNetSalary($grossSalary, $employee);
            var_dump($netSalary);

        return [
            'total_normal_hours' => $hourCalculations['total_normal_hours'],
            'total_overtime_hours' => $hourCalculations['total_overtime_hours'],
            'total_holiday_hours' => $hourCalculations['total_holiday_hours'],
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
        ];
    }

    /**
     * Summary of calculateTotalHoursWorked
     * @param mixed $hoursWorked
     * @return array
     */
    private function calculateTotalHoursWorked(mixed $hoursWorked): array
    {
        $totalNormalHours = $hoursWorked->sum('normal_hours');
        $totalOvertimeHours = $hoursWorked->sum('overtime_hours');
        $totalHolidayHours = $hoursWorked->sum('holiday_hours');

        return [
            'total_normal_hours' => $totalNormalHours,
            'total_overtime_hours' => $totalOvertimeHours,
            'total_holiday_hours' => $totalHolidayHours,

        ];
    }

    /**
     * Summary of calculateGrossSalary
     * @param array $totalNormalHours
     * @param array $totalOvertimeHours
     * @param array $totalHolidayHours
     * @param Employee $employee
     * @return float
     */
    private function calculateGrossSalary(array $totalNormalHours, array $totalOvertimeHours, array $totalHolidayHours, Employee $employee): float
    {
        // Convierte los minutos a fracciones de hora
        $normalHoursWithMinutes = $totalNormalHours['hours'] + ($totalNormalHours['minutes'] / 60);
        $overtimeHoursWithMinutes = $totalOvertimeHours['hours'] + ($totalOvertimeHours['minutes'] / 60);
        $holidayHoursWithMinutes = $totalHolidayHours['hours'] + ($totalHolidayHours['minutes'] / 60);

        $totalNormal = $normalHoursWithMinutes * $employee->normal_hourly_rate;
        $totalOvertime = $overtimeHoursWithMinutes * $employee->overtime_hourly_rate;
        $totalHoliday = $holidayHoursWithMinutes * $employee->holiday_hourly_rate;
    
        // Devuelve la suma total
        return $totalNormal + $totalOvertime + $totalHoliday;
    }

    private function calculateNetSalary(float $grossSalary, Employee $employee): float
    {
        if ($grossSalary < 0 || $employee->irpf < 0) {
           return 0;
        }
    
        return $grossSalary - ($grossSalary * ($employee->irpf / 100));
    }
    
}

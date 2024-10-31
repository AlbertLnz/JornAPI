<?php

namespace App\DTO\Salary;

use App\Models\Salary;

class ShowSalaryDTO{

    public function __construct(
        public string $startDate, 
        public string $endDate, 
        public float $total_normal_hours, 
        public float $total_overtime_hours, 
        public float $total_night_hours,
        public float $total_holiday_hours,
        public float $total_gross_salary,
        public float $total_net_salary){}

    public static function fromSalary(Salary $salary): self
    {
        return new self(
            $salary->start_date,
            $salary->end_date,
            $salary->total_normal_hours,
            $salary->total_overtime_hours,
            $salary->total_night_hours,
            $salary->total_holiday_hours,
            $salary->total_gross_salary,
            $salary->total_net_salary
        );
    }
}
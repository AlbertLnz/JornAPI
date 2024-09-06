<?php 

declare(strict_types=1);    

namespace App\DTO\Employee;

use App\Models\Employee;

class ShowEmployeeDTO{


    public function __construct(
        public string $name,
        public string $company_name,
        public float $normal_hourly_rate,
        public float $overtime_hourly_rate,
        public float $night_hourly_rate,
        public float $holiday_hourly_rate, 
        public float $irpf
    ){}

    public static function fromEmployee(Employee $employee): self
    {
        return new self(
            $employee->name,
            $employee->company_name,
            $employee->normal_hourly_rate,
            $employee->overtime_hourly_rate,
            $employee->night_hourly_rate,
            $employee->holiday_hourly_rate,
            $employee->irpf
        );
    }
}
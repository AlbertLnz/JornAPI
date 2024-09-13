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
            $employee->company_name??'',
            (float)$employee->normal_hourly_rate,
            (float)$employee->overtime_hourly_rate,
            (float)$employee->night_hourly_rate??0.0,
            (float)$employee->holiday_hourly_rate ?? 0.0,
            (float)$employee->irpf??0.0
        );
    }
}
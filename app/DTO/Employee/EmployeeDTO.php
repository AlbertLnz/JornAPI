<?php 

declare(strict_types=1);    

namespace App\DTO\Employee;

use App\DTO\DTOInterface;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class  EmployeeDTO implements DTOInterface{


    public function __construct(
        public string $name,
        public string $company_name,
        public float $normal_hourly_rate,
        public float $overtime_hourly_rate,
        public float $holiday_hourly_rate, 
        public float $irpf
    ){}

    public static function fromModel(Model $employee): self
    {
        return new self(
            $employee->name,
            $employee->company_name??'',
            (float)$employee->normal_hourly_rate,
            (float)$employee->overtime_hourly_rate,
            (float)$employee->holiday_hourly_rate ?? 0.0,
            (float)$employee->irpf??0.0
        );
    }

    public static function toArray(array $data): array
    {
        return [
            'name' => $data['name'],
            'company_name' => $data['company_name'],
            'normal_hourly_rate' => $data['normal_hourly_rate'],
            'overtime_hourly_rate' => $data['overtime_hourly_rate'],
            'holiday_hourly_rate' => $data['holiday_hourly_rate'],
            'irpf' => $data['irpf'],
        ];
    } 
}
<?php
declare(strict_types=1);

namespace App\Services\Employee;

use App\Exceptions\UserNotFound;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeUpdateService{

    public function execute(?string $name, 
                            ?string $company, 
                            ?float $normalHourlyRate, 
                            ?float $overtimeHourlyRate, 
                            ?float $nightHourlyRate, 
                            ?float $holidayHourlyRate, 
                            ?float $irpf, 
                            ?string $uuid): Employee{
        $user = User::where('id', $uuid)->first();
        DB::transaction(function () use ($user, $name, $company, $normalHourlyRate, $overtimeHourlyRate, $nightHourlyRate, $holidayHourlyRate, $irpf) {
            if(!$user){
                throw new UserNotFound();
            }
    
            if($name != null){
                $user->employee->name = $name;
            }
    
            if($company != null){
                $user->employee->company_name = $company;
            }
    
            if($normalHourlyRate != null){
                $user->employee->normal_hourly_rate = $normalHourlyRate;
            }
    
            if($overtimeHourlyRate != null){
                $user->employee->overtime_hourly_rate = $overtimeHourlyRate;
            }
    
            if($nightHourlyRate != null){
                $user->employee->night_hourly_rate = $nightHourlyRate;
            }
    
            if($holidayHourlyRate != null){
                $user->employee->holiday_hourly_rate = $holidayHourlyRate;
            }
    
            if($irpf != null){
                $user->employee->irpf = $irpf;
            }
        
            $user->employee->save();
        });
       
        return $user->employee;
    }
}
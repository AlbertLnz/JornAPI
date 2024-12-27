<?php

declare(strict_types=1);

namespace App\Services\Employee;


use App\Services\User\RegisterUserService;
use Illuminate\Support\Facades\DB;

class RegisterEmployeeService{
    /**
     * Summary of __construct
     * @param \App\Services\User\RegisterUserService $registerUserService
     */
    public function __construct(private RegisterUserService $registerUserService){}
    /**
     * Summary of execute
     * @param string $name
     * @param string $email
     * @param string $password
     * @param float $normalHourlyRate
     * @param float $overtimeHourlyRate
     * @param float $nightHourlyRate
     * @param float $holidayHourlyRate
     * @param float $irpf
     * @return void
     */
    public function execute(string $name , string $email, string $password,  float $normalHourlyRate, float $overtimeHourlyRate, float $holidayHourlyRate, float $irpf,): void
    {
        $employee = [
            'name' => $name,
            'company_name' => 'Company',
            'normal_hourly_rate' => $normalHourlyRate,
            'overtime_hourly_rate' => $overtimeHourlyRate,
            'holiday_hourly_rate' => $holidayHourlyRate,
            'irpf' => $irpf
        ];


        DB::transaction(function () use ( $email, $password, $employee) {
           $user= $this->registerUserService->execute($email, $password);
          
            $user->employee()->create([
                'name' => $employee['name'],
                'company_name' => 'Company',
                'normal_hourly_rate' => $employee['normal_hourly_rate'],
                'overtime_hourly_rate' => $employee['overtime_hourly_rate'],
                'holiday_hourly_rate' => $employee['holiday_hourly_rate'],
                'irpf' => $employee['irpf']?? 0.0
            ]);
            DB::afterCommit(function () use ($user) {
                $user->assignRole('employee');

            });
      //   SendRegisterNotification::dispatch($user);

        });
    
          
      

      
       
    }
}
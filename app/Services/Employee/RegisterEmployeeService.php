<?php

declare(strict_types=1);

namespace App\Services\Employee;

use App\Jobs\SendRegisterNotification;
use App\Models\User;
use App\Services\User\RegisterUserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterEmployeeService
{
    /**
     * Summary of __construct
     */
    public function __construct(private RegisterUserService $registerUserService) {}

    /**
     * Summary of execute
     *@param array $data
     */
    public function execute(array $data): void
    {
        
        DB::transaction(function () use ($data, ) {
            
            $user = $this->registerUserService->execute($data['user']['email'], $data['user']['password']);

            $user->employee()->create([
                'name' => $data['name'],
                'company_name' => $data['company_name'],
                'normal_hourly_rate' => $data['normal_hourly_rate'],
                'overtime_hourly_rate' => $data['overtime_hourly_rate'],
                'holiday_hourly_rate' => $data['holiday_hourly_rate'],
                'irpf' => $data['irpf'],
            ]);
            DB::afterCommit(function () use ($user) {
                $user->assignRole('employee');
                $this->sendRegisterNotification($user);
            }); 
        });
    }
    /**
     * Summary of sendRegisterNotification
     * @param \App\Models\User $user
     * @return void
     */
    private function sendRegisterNotification(User $user): void {
        try {
            SendRegisterNotification::dispatch($user);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    
}

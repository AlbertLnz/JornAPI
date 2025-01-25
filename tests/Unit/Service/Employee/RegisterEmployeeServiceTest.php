<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Employee;

use App\DTO\Employee\RegisterEmployeeDTO;
use App\Models\User;
use App\Services\Employee\RegisterEmployeeService;
use App\Services\User\RegisterUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterEmployeeServiceTest extends TestCase
{
    use DatabaseTransactions;

    private RegisterEmployeeService $service;

    private RegisterUserService $registerUserService;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registerUserService = new RegisterUserService;
        $this->service = new RegisterEmployeeService($this->registerUserService);
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(RegisterEmployeeService::class, $this->service);
    }

    public function testregister_employee_servicewithvaliddata()
    {
        $data = [
            'email' => 'peter@peter.com',
            'password' => '12345678',
            'name' => 'peter',
            'company_name' => 'comapany',
            'normal_hourly_rate' => 1,
            'overtime_hourly_rate' => 1,
            'holiday_hourly_rate' => 1,
            'irpf' => 1,
        ];
        

        $this->service->execute(RegisterEmployeeDTO::toArray($data));

        $this->assertDatabaseHas('employees', [
            'name' => 'peter',
        ]);

    }
}

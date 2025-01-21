<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Employee;

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

        $this->service->execute('peter', 'peter@peter.com', 'comapany', '12345678', 1, 1, 1, 1);

        $this->assertDatabaseHas('employees', [
            'name' => 'peter',
        ]);

    }
}

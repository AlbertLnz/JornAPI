<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Employee;

use App\Models\Employee;
use App\Models\User;
use App\Services\Employee\EmployeeUpdateService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateEmployeeServiceTest extends TestCase{
use DatabaseTransactions;
    private EmployeeUpdateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new EmployeeUpdateService();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(EmployeeUpdateService::class, $this->service);    
    }

    public function testUpdateEmployeeServicewithvaliddata()
    {
        $user = Employee::factory()->create();

        $employee = $this->service->execute('peter','Facebook',10.00,10.30,13.00,15.00,4.00 , $user->user->id);

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals('peter', $employee->name);
        $this->assertEquals('Facebook', $employee->company_name);
        $this->assertEquals(10.00, $employee->normal_hourly_rate);
        $this->assertEquals(10.30, $employee->overtime_hourly_rate);
        $this->assertEquals(13.00, $employee->night_hourly_rate);
        $this->assertEquals(15.00, $employee->holiday_hourly_rate);
        $this->assertEquals(4.00, $employee->irpf);
    }
    
}
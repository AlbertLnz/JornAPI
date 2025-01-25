<?php

namespace Tests\Feature\Employee;

use App\Models\Employee;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UpdateEmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Employee $employee;

    private TokenService $tokenService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employee = Employee::factory()->create();
        $this->tokenService = new TokenService;
    }

    public function test_update_employee_success(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $updateEmployee = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->putJson('/api/employee', [
            'name' => 'John Doe',
            'company_name' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 20.0,
            'irpf' => 15.0,
        ]);

        $updateEmployee->assertStatus(200);
        $updateEmployee->assertJsonStructure([
            'message',
        ]);
        $updateEmployee->assertJson([
            'message' => 'Employee updated successfully',
        ]);

    }

    public function test_update_employee_empty_values(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $updateEmployee = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->putJson('/api/employee', [
          
        ]);

        $updateEmployee->assertStatus(status: 200);
        $updateEmployee->assertJsonStructure([
            'message',
            'employee',
        ]);
        $updateEmployee->assertJson([
            'message' => 'Employee updated successfully',
        ]);
    }
}

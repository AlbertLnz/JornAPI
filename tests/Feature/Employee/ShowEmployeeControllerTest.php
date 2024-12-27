<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShowEmployeeControllerTest extends TestCase
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

    public function test_show_employee_success(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $showEmployee = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/employee');

        $showEmployee->assertStatus(200);
        $showEmployee->assertJsonStructure([
            'employee' => ['name',
                'company_name',
                'normal_hourly_rate',
                'overtime_hourly_rate',
                'holiday_hourly_rate',
                'irpf', ],

        ]);
    }
}

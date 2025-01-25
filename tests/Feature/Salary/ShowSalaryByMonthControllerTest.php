<?php

namespace Tests\Feature\Salary;

use App\Models\Employee;
use App\Models\Salary;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShowSalaryByMonthControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Employee $employee;

    private Salary $salary;

    private TokenService $tokenService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
        $this->employee = Employee::factory()->create();
        $this->salary = Salary::create([
            'employee_id' => $this->employee->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-31',
            'total_normal_hours' => 160,
            'total_overtime_hours' => 20,
            'total_holiday_hours' => 8,
            'total_gross_salary' => 2000,
            'total_net_salary' => 1800,
        ]);

    }

    public function test_show_salary_by_month_success(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $request = new Request;
        $request->setUserResolver(fn () => $this->employee->user);
        $showSalaryByMonth = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson("/api/salary?year=2024&month=10");
        $showSalaryByMonth->assertStatus(200);

    }

    public function test_show_salary_by_month_fail(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $request = new Request;
        $request->setUserResolver(fn () => $this->employee->user);
        $showSalaryByMonth = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson("/api/salary?year=2024&month=11");
        $showSalaryByMonth->assertStatus(404);

    }
}

<?php

namespace Tests\Feature\Dashboard;

use App\Events\HourSessionRegistered;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Models\User;
use App\Services\Salary\SalaryService;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Employee $employee;

    private HourSession $hourSession;

    private TokenService $tokenService;

    private HourWorked $hourWorked;

    private SalaryService $salaryService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
        $this->employee = Employee::factory()->create(['user_id' => $this->user->id]);
        $this->hourSession = HourSession::factory()->create([
            'employee_id' => $this->employee->id,
        ]);
        $this->hourWorked = HourWorked::factory()->create([
            'hour_session_id' => $this->hourSession->id,
        ]);
        

    }

    public function test_show_dashboard_success(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        $this->actingAs($this->user);
        
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $request = new Request;
        $request->setUserResolver(fn () => $this->employee->user);
        $showDashboard = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson(route('dashboard'));
        $showDashboard->assertStatus(200);
    }
}

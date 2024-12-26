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

class DashboardControllerTest extends TestCase{
use DatabaseTransactions;
    private Employee $employee;
    private HourSession $hourSession;
    private TokenService $tokenService;
    private HourWorked $hourWorked;
    private SalaryService $salaryService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->employee = Employee::factory()->create();
        $this->hourSession = HourSession::factory()->create([
            'employee_id' => $this->employee->id
        ]);
        $this->hourWorked = HourWorked::factory()->create([
            'hour_session_id' => $this->hourSession->id
        ]);
        event(new HourSessionRegistered($this->employee->id, $this->hourSession->date));
       
        
    }

    public function testShowDashboardSuccess(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); 
        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);
        $showDashboard =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/dashboard');
        $showDashboard->assertStatus(200);
        }
}
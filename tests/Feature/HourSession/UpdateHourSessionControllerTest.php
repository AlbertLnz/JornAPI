<?php 

namespace Tests\Feature\HourSession;

use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UpdateHourSessionControllerTest extends TestCase{
use DatabaseTransactions;
    private Employee $employee;
    private HourSession $hourSession;
    private TokenService $tokenService;
    private HourWorked $hourWorked;

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
        
    }
    public function testUpdateHourSessionSuccess(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); 
        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);
        $updateHourSession =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/hour_session?date=' . $this->hourSession->date, [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
        ]);
        $updateHourSession->assertStatus(200);
        $updateHourSession->assertJsonStructure([
            'message'
        ]);
        $this->assertDatabaseHas('hour_sessions', [
            'employee_id' => $this->employee->id,
            'date' => $this->hourSession->date,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
        ]);
    }
}
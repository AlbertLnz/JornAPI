<?php 
namespace Tests\Feature\HourSession;

use App\Models\Employee;
use App\Models\HourSession;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DeleteHourSessionControllerTest extends TestCase{
use DatabaseTransactions;
    private Employee $employee;
    private HourSession $hourSession;
    private TokenService $tokenService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->employee = Employee::factory()->create();
        $this->hourSession = HourSession::factory()->create([
            'employee_id' => $this->employee->id
        ]);
        
    }

    public function testDeleteHourSessionSuccess(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); 
        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);
        $deleteHourSession =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/hour_session?date=' . $this->hourSession->date);
        $deleteHourSession->assertStatus(200);
        $deleteHourSession->assertJsonStructure([
            'message'
        ]);
    }
}
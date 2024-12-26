<?php 

namespace Tests\Feature\HourSession;

use App\Exceptions\TodayDateException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Services\Token\TokenService;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RegisterHourSessionControllerTest extends TestCase{
    use DatabaseTransactions;

    private TokenService $tokenService;
    private Employee $employee;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->employee = Employee::factory()->create();

    }
    public function testRegisterHourSessionSuccess(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //

        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);

       
        $register =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/hour_session', [
            'date' => '2024-02-13',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => 'NORMAL'
        ]);

        $register->assertStatus(201);

        $register->assertJsonStructure([
            'message',
        ]);
        $register->assertJson([
            'message' => 'Hour worked registered successfully'
        ]);
    }

    public function testRegisterHourSessionFail(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //

        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);

       
        $register =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/hour_session', [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => 'NORMAL'
        ]);

        $register->assertStatus(422);

        $register->assertJsonStructure([
            'errors',
        ]);
       
    }

    public function testRegisterHourSessionDayTomorrow(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //

        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);

       
        $register =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/hour_session', [
            'date' => '2024-12-30',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
        ]);

        $register->assertStatus(400);
  
       
    }

    public function testRegisterHourSessionExist(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //
        HourSession::create([
            'employee_id' => $this->employee->id,
            'date' => '2024-02-13',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
        ]);

        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);

       
        $register =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/hour_session', [
            'date' => '2024-02-13',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
        ]);

        $register->assertStatus(422);
    
    }

    public function testRegisterHourSessionLess2Hours(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //

        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);

       
        $register =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/hour_session', [
            'date' => '2024-02-13',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'planned_hours' => 4,
        ]);

        $register->assertStatus(400);
        $register->assertJsonStructure([
            'message',
        ]);
        $register->assertJson([
            'message' => 'The hours worked must be between 2 and 12. You provided 1.'
        ]);
    
    }

    public function testRegisterHourSessionMore12Hours(): void{
        $token =$this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //

        $request = new Request();
        $request->setUserResolver(fn() => $this->employee->user);

       
        $register =$this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/hour_session', [
            'date' => '2024-02-13',
            'start_time' => '09:00',
            'end_time' => '22:00',
            'planned_hours' => 14,
        ]);

        $register->assertStatus(400);
        $register->assertJsonStructure([
            'message',
        ]);
        $register->assertJson([
            'message' => 'The hours worked must be between 2 and 12. You provided 13.'
        ]);
    
    }
    
}
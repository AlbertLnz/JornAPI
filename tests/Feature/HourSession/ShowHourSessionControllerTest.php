<?php

namespace Tests\Feature\HourSession;

use App\Models\Employee;
use App\Models\HourSession;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShowHourSessionControllerTest extends TestCase
{
    use DatabaseTransactions;
    private Employee $employee;

    private HourSession $hourSession;

    private TokenService $tokenService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
        $this->employee = Employee::factory()->create();
        $this->hourSession = HourSession::factory()->create([
            'employee_id' => $this->employee->id,
        ]);

    }

    public function test_show_hour_session_success(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $request = new Request;
        $request->setUserResolver(fn () => $this->employee->user);
        $showHourSession = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/hour_session?date='.$this->hourSession->date);
        $showHourSession->assertStatus(200);
        $showHourSession->assertJsonStructure([

            'hour_session',
        ]);
    }

    public function test_show_hour_session_fail(): void
    {
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600);
        $request = new Request;
        $request->setUserResolver(fn () => $this->employee->user);
        $showHourSession = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/hour_session?date=2024-02-13');
        $showHourSession->assertStatus(404);
        $showHourSession->assertJsonStructure([
            'message',
        ]);
    }
}

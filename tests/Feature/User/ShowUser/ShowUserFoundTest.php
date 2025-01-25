<?php

namespace Tests\Feature\User\ShowUser;

use App\DTO\User\UserDTO;
use App\Models\Employee;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShowUserFoundTest extends TestCase
{
    use DatabaseTransactions;

    private TokenService $tokenService;

    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
        $this->employee = Employee::factory()->create();

    }

    public function test_show_user_found()
    {
        $user = UserDTO::fromModel($this->employee->user);
       // $this->actingAs($this->employee->user->auth()->user());
        $token = $this->tokenService->generateToken($this->employee->user_id);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //

        $showUser = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/user/show');

        $showUser->assertStatus(200);
        $showUser->assertJsonStructure([
            'message',
            'user',
        ]);
        $showUser->assertJson([
            'message' => 'User found successfully',
            'user' => UserDTO::toArray($this->employee->user->toArray()),
       
        ]);

    }
}

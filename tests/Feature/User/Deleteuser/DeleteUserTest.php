<?php

namespace Tests\Feature\User\Deleteuser;

use App\Models\Employee;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    private TokenService $tokenService;

    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
        $this->employee = Employee::factory()->create();
    }

    public function test_delete_user()
    {
        $user = $this->employee->user;
        $token = $this->tokenService->generateToken($user->id);
        Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //

        $deleteUser = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/user/delete');
        $deleteUser->assertStatus(200);
        $deleteUser->assertJsonStructure([
            'message',
        ]);
    }
}

<?php

namespace Tests\Feature\User\UpdateUser;

use App\Models\Employee;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UpdateEmailBadDataTest extends TestCase
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

    public function test_update_bad_email()
    {
        $user = $this->employee->user;

       $request = new Request();
        $request->setUserResolver(fn () => $user);
        $token = $this->tokenService->generateToken($user->id);
        Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //

        $updateUser = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->putJson('/api/user/update', [
            'email' => '',
         
        ]);
        $updateUser->assertStatus(422);

    }
}

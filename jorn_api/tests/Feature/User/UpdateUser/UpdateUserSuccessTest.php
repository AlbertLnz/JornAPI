<?php
namespace Tests\Feature\User\UpdateUser;
use App\Models\Employee;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;


class UpdateUserSuccessTest extends TestCase
{   
    use DatabaseTransactions;
    private TokenService $tokenService;
    private Employee $employee;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->employee = Employee::factory()->create();
    }
    public function testUpdateUserSuccess()
    {
        $employee = Employee::factory()->create();
        $user = $employee->user;
        $token =$this->tokenService->generateToken($user->id,$user->roles);
        Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //

        $updateUser = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->putJson('/api/user/update', [
    'email' => 'XuqFP@example.com',
    'password' => 'password',

]);
        $updateUser->assertStatus(200);

        $updateUser->assertJsonStructure([
            'message',
            'user'
        ]);
    }

}
<?php 

namespace Tests\Feature\User\UpdateUser;

use App\Models\Employee;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UpdateUserBadDataTest extends TestCase
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

    public function testUpdateUserBadData()
    {
        $user = $this->employee->user;
        $token =$this->tokenService->generateToken($user->id,$user->roles);
        Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //  

        $updateUser = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->putJson('/api/user/update', [
    'email' => '',
    'password' => '',
]);
        $updateUser->assertStatus(422);

}
}
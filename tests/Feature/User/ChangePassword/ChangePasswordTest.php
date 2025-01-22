<?php 

declare(strict_types=1);

namespace Tests\Feature\User\ChangePassword;

use Tests\TestCase;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ChangePasswordTest extends TestCase
{
    use DatabaseTransactions;

    private TokenService $tokenService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
    }

    public function testChangePassword()
    {
        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole('employee');
        $token = $this->tokenService->generateToken($user->id);
        $data = [
            'old_password' => 'password',
            'new_password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];
        Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //  

        $changePassword = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->putJson(route('change_password'), $data);

        $changePassword->assertStatus(200);
        $changePassword->assertJsonStructure([
            'message',
        ]);
        $changePassword->assertJson([
            'message' => 'Password changed successfully',
        ]);

      $this->assertTrue(Hash::check('new_password', $user->fresh()->password));
    }

    public function testChangePasswordWithWrongOldPassword()
    {
        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole('employee');
        $token = $this->tokenService->generateToken($user->id);
        $data = [
            'old_password' => 'wrong_password',
            'new_password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];
        Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //  

        $changePassword = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->putJson(route('change_password'), $data);

        $changePassword->assertStatus(400);
        $changePassword->assertJsonStructure([
            'message',
           
        ]);
    }
    }
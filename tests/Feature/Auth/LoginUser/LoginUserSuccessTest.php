<?php

namespace Tests\Feature\Auth\LoginUser;

use App\Models\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginUserSuccessTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_user_success()
    {
        $employee = Employee::factory()->create();
        $login = $this->postJson('/api/login', [
            'email' => $employee->user->email,
            'password' => 'password',
        ]);

        /*   $login->assertJsonStructure([
              'token',
              'refreshToken',
          ]); */

        $login->assertJson([
            'token' => $login->json('token'),
            'refreshToken' => $login->json('refreshToken'),
        ]);

        $login->assertStatus(200);
    }
}

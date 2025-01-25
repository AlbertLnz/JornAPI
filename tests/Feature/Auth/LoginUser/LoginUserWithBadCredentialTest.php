<?php

namespace Tests\Feature\Auth\LoginUser;

use App\Models\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginUserWithBadCredentialTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_user_with_bad_credential()
    {

        $employee = Employee::factory()->create();
        $login = $this->postJson('/api/login', [
            'email' => $employee->user->email,
            'password' => 'password2',
        ]);
        $login->assertStatus(401);
    }

    public function test_login_user_with_null_credential()
    {
        $login = $this->postJson('/api/login', [
            'email' => null,
            'password' => null,
        ]);
        $login->assertStatus(422);
    }

    public function test_login_user_with_empty_credential()
    {
        $login = $this->postJson('/api/login', [
            'email' => '',
            'password' => '',
        ]);
        $login->assertStatus(422);
    }

    public function test_login_user_with_injection_sql()
    {
        $login = $this->postJson('/api/login', [
            'email' => '1 OR 1=1',
            'password' => '1 OR 1=1',
        ]);
        $login->assertStatus(422);
    }

    public function test_login_user_with_injection_xss()
    {
        $login = $this->postJson('/api/login', [
            'email' => '<script>alert(1)</script>',
            'password' => '<script>alert(1)</script>',
        ]);
        $login->assertStatus(422);
    }

    public function test_login_user_not_exist()
    {
        $login = $this->postJson('/api/login', [
            'email' => '1H2Y5@example.com',
            'password' => 'password',
        ]);
        $login->assertStatus(404);
    }
}


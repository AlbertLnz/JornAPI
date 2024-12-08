<?php

namespace Tests\Feature\Auth\LoginUser;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginUserWithBadCredentialTest extends TestCase
{
    use DatabaseTransactions;
    public function testLoginUserWithBadCredential(){

        $employee = Employee::factory()->create();
       $login = $this->postJson('/api/login', [
            'email' => $employee->user->email,
            'password' => 'password2',
       ]);
       $login->assertStatus(401);
    }

    public function testLoginUserWithNullCredential(){
       $login = $this->postJson('/api/login', [
            'email' => null,
            'password' => null,
       ]);
       $login->assertStatus(422);
    }

    public function testLoginUserWithEmptyCredential(){
       $login = $this->postJson('/api/login', [
            'email' => '',
            'password' => '',
       ]);
       $login->assertStatus(422);
    }

    public function testLoginUserWithInjectionSQL(){
       $login = $this->postJson('/api/login', [
            'email' => '1 OR 1=1',
            'password' => '1 OR 1=1',
       ]);
       $login->assertStatus(422);
    }
}
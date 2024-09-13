<?php

namespace Tests\Feature\RegisterEmployee;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterEmployeeWithBadDataTest extends TestCase{
use DatabaseTransactions;
    public function testRegisterEmployeeWithBadEmail(): void{
        $register = $this->post('/api/register', [  
            'email' => 'JZQ3xample.com',
            'password' => 'password',
            'name' => 'John Doe',
            'company_name' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,

        ]);
        $register->assertStatus(422);
    }

    public function testRegisterEmployeeWithBadPassword(): void{
        $register = $this->post('/api/register', [
            'email' => fake()->email(),
            'password' => '',
            'name' => 'John Doe',
            'company_name' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,

        ]);
        $register->assertStatus(422);
    }

    public function testRegisterEmployeeWithSqlInjection(): void{
        $register = $this->post('/api/register', [
            'email' => "' OR 1=1",
            'password' => 'password',
            'name' => 'John Doe',
            'company_name' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,

        ]);

        $register->assertStatus(422);
    }

    public function testRegisterEmployeeWithNullData(): void{
        $register = $this->post('/api/register', [
          

        ]);
        $register->assertStatus(422);
    }
}
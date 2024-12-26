<?php

declare(strict_types=1);
namespace Tests\Feature\Employee;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;



class RegisterEmployeeSuccessTest extends TestCase{

use DatabaseTransactions;
    public function testRegisterEmployeeSuccess(): void{

       $register = $this->postJson('/api/register', [
            'email' => fake()->email(),
            'password' => 'password',
            'name' => 'John Doe',
            'company_name' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 25.0,
            'irpf' => 30.0
            
        ]);

     

        
        $register->assertStatus(201);

        $register->assertJsonStructure([
            'message',
        ]);
    }

}
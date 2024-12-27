<?php

namespace Tests\Feature\Employee;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterEmployeeWithBadDataTest extends TestCase
{
    use DatabaseTransactions;

    public function test_register_employee_with_bad_email(): void
    {
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

    public function test_register_employee_with_bad_password(): void
    {
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

    public function test_register_employee_with_sql_injection(): void
    {
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

    public function test_register_employee_with_null_data(): void
    {
        $register = $this->post('/api/register', [

        ]);
        $register->assertStatus(422);
    }
}

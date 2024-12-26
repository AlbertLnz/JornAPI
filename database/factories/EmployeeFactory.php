<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $user->assignRole('employee');
        return [
            'name' => fake()->name(),
            'company_name' => fake()->company(),
            'normal_hourly_rate' => fake()->numberBetween(10, 100),
            'overtime_hourly_rate' => fake()->numberBetween(10, 100),
            'holiday_hourly_rate' => fake()->numberBetween(10, 100),
            'irpf' => fake()->numberBetween(10, 100),
            'user_id' => $user->id
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HourSession>
 */
class HourSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employee = Employee::factory()->create();

        return [
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'planned_hours' => $this->faker->numberBetween(1, 10),
            'work_type' => $this->faker->randomElement(['is_normal', 'is_holiday', 'is_overtime']),
            'employee_id' => $employee->id,
        ];

    }
}

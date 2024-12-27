<?php

namespace Database\Factories;

use App\Models\HourSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HourWorked>
 */
class HourWorkedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hourSession = HourSession::factory()->create();

        return [
            'hour_session_id' => $hourSession->id,
            'normal_hours' => $this->faker->numberBetween(1, 10),
            'overtime_hours' => 0,
            'holiday_hours' => 0,
        ];
    }
}

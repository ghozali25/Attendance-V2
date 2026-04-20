<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $i = fake()->unique()->randomElement([0, 1, 2]);

        $shifts = [
            ['name' => 'Shift Pagi', 'start_time' => '07:00', 'end_time' => '15:00'],
            ['name' => 'Shift Sore', 'start_time' => '15:00', 'end_time' => '23:00'],
            ['name' => 'Shift Malam', 'start_time' => '23:00', 'end_time' => '07:00'],
        ];

        return $shifts[$i];
    }
}

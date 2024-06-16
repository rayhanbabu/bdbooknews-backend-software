<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class HallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return   [
            'uni_id' => fake()->randomNumber(),
            'hall_en' => fake()->name(1),
            'hall_bn' => fake()->paragraph(1),
        ];
    }

  
}

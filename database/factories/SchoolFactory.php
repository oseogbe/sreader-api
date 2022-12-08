<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->words(2, true) . ' ' . fake()->randomElement(['school', 'academy']),
            'logo' => fake()->regexify('[A-Za-z0-9]{30}') . fake()->randomElement(['.jpg', '.png']),
            'location' => fake()->sentence
        ];
    }
}

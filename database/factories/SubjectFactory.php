<?php

namespace Database\Factories;

use App\Models\Level;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'school_id' => School::all()->random()->id,
            'level_id' => Level::all()->random()->id,
            'teacher_id' => Teacher::exists() ? Teacher::all()->random()->id : null,
            'title' => fake()->text(20),
            'description' => fake()->paragraphs(5, true)
        ];
    }
}

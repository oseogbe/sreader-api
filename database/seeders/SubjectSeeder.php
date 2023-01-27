<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['name' => 'Mathematics', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'English', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Basic Science', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Introductory Technology', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Social Studies', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Home Economics', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Business Studies', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Computer Studies', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Technical Drawing', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Geography', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Further Mathematics', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Literature-in-English', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Economics', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Physics', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Chemistry', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Biology', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Accounting', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Marketing', 'description' => fake()->paragraphs(3, true)],
            ['name' => 'Civic Education', 'description' => fake()->paragraphs(3, true)],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}

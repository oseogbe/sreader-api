<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Teacher::truncate();

        $a = 0;

        while ($a <= 10) {
            Teacher::create([
                'school_id' => School::inRandomOrder()->first()->id,
                'firstname' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'email' => fake()->freeEmail(),
                'password' => bcrypt('12345678'),
            ]);

            $a++;
        }
    }
}

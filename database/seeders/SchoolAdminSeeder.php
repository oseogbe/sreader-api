<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SchoolAdmin::truncate();

        $a = 0;

        while ($a <= 5) {
            SchoolAdmin::create([
                'school_id' => School::all()->random()->id,
                'firstname' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'email' => fake()->freeEmail(),
                'password' => bcrypt(12345678)
            ]);

            $a++;
        }
    }
}

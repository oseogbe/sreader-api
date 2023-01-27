<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = Level::all();

        foreach ($levels as $level) {
            $i = 0;

            while ($i < 3) {
                $level->subjects()->attach(Subject::all()->random()->id);
                $i++;
            }
        }
    }
}

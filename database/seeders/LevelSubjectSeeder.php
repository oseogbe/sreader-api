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
        $subjects = Subject::all();

        $subjects->each(function($subject) {
            $level_ids = Level::all()->pluck('id')->toArray();

            $level_ids_keys =  array_rand($level_ids, mt_rand(3,6));

            $subject_level_ids = array_values(array_intersect_key($level_ids, array_flip($level_ids_keys)));

            $subject->update(['level_id' => $subject_level_ids]);
        });
    }
}

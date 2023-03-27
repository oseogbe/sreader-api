<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentParentRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10; $i++) {
            $student_id = Student::all()->random()->id;
            $parent_id = StudentParent::all()->random()->id;

            DB::table('parent_student')->insert([
                'student_id' => $student_id,
                'parent_id'  => $parent_id
            ]);
        }
    }
}

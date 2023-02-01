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
        Subject::truncate();

        $subjects = ['Mathematics','English','Basic Science','Basic Technology','Social Studies','Home Economics','Business Studies','Computer Studies','PHE','Technical Drawing','Geography','Further Mathematics','Literature-in-English','Economics','Physics','Chemistry','Biology','Accounting','Marketing','Civic Education'];

        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }
    }
}

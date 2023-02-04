<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::truncate();

        $levels = ['JS 1 (Year 7)', 'JS 2 (Year 8)', 'JS 3 (Year 9)', 'SS 1 (Year 10)', 'SS 2 (Year 11)', 'SS 3 (Year 12)'];

        foreach ($levels as $key => $level) {
            Level::create(['name' => $level]);
        }
    }
}

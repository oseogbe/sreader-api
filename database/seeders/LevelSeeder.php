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
        $levels = ['JS 1', 'JS 2', 'JS 3', 'SS 1', 'SS 2', 'SS 3'];

        foreach ($levels as $key => $level) {
            Level::create(['name' => $level]);
        }
    }
}

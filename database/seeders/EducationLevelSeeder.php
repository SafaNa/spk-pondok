<?php

namespace Database\Seeders;

use App\Models\Master\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    public function run()
    {
        // Formal
        $formalLevels = ['SD', 'SMP', 'SMA', 'SMK'];
        foreach ($formalLevels as $level) {
            EducationLevel::create(['name' => $level, 'type' => 'formal']);
        }

        // Religious (Diniyah)
        $religiousLevels = ['Ula', 'Wustho', 'Ulya'];
        foreach ($religiousLevels as $level) {
            EducationLevel::create(['name' => $level, 'type' => 'religious']);
        }
    }
}

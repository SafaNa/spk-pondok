<?php

namespace Database\Seeders;

use App\Models\Master\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        AcademicYear::create([
            'name' => '2023/2024',
            'status' => 'inactive',
            'spp_amount' => 150000,
            'max_leaves' => 7,
        ]);

        AcademicYear::create([
            'name' => '2024/2025',
            'status' => 'active',
            'spp_amount' => 200000,
            'max_leaves' => 7,
        ]);

    }
}

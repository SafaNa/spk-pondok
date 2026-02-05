<?php

namespace Database\Seeders;

use App\Models\Master\AcademicYear;
use App\Models\Master\Period;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        AcademicYear::create([
            'name' => '2023/2024',
            'status' => 'inactive',
            'spp_amount' => 150000,
        ]);

        AcademicYear::create([
            'name' => '2024/2025',
            'status' => 'active',
            'spp_amount' => 200000,
        ]);

        // Also create periods for the active academic year
        $activeYear = AcademicYear::where('status', 'active')->first();
        if ($activeYear) {
            Period::create([
                'academic_year_id' => $activeYear->id,
                'name' => 'Semester Ganjil 2024/2025',
                'is_active' => false
            ]);

            Period::create([
                'academic_year_id' => $activeYear->id,
                'name' => 'Semester Genap 2024/2025',
                'is_active' => true
            ]);
        }
    }
}

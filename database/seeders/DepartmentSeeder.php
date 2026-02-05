<?php

namespace Database\Seeders;

use App\Models\Master\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            [
                'code' => 'DEPT_001',
                'name' => 'Pendidikan & Pengajaran Madrasan Diniyah Annuqayah Latee II',
                'acronym' => 'PENDIDIKAN',
                'description' => 'Mengatur pendidikan dan pengajaran di Madrasan Diniyah',
            ],
            [
                'code' => 'DEPT_002',
                'name' => 'Pembinaan Akhlak',
                'acronym' => 'AKHLAK',
                'description' => 'Membina akhlak dan perilaku santri',
            ],
            [
                'code' => 'DEPT_003',
                'name' => 'Pendidikan Formal',
                'acronym' => 'FORMAL',
                'description' => 'Mengatur pendidikan formal santri',
            ],
            [
                'code' => 'DEPT_004',
                'name' => 'Keamanan & Ketertiban',
                'acronym' => 'KEAMANAN',
                'description' => 'Menjaga keamanan dan ketertiban pondok',
            ],
            [
                'code' => 'DEPT_005',
                'name' => 'Peribadatan dan SKIA',
                'acronym' => 'PERIBADATAN',
                'description' => 'Mengatur ibadah dan kegiatan SKIA',
            ],
            [
                'code' => 'DEPT_006',
                'name' => 'Pengajian Al-Qur\'an dan Kitab',
                'acronym' => 'PENGAJIAN',
                'description' => 'Mengatur pengajian Al-Qur\'an dan kitab kuning',
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}

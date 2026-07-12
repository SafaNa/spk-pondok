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
                'name' => 'Departemen Pembinaan Akhlak',
                'acronym' => 'DPA',
                'description' => 'Membina akhlak dan perilaku santri',
            ],
            [
                'code' => 'DEPT_002',
                'name' => 'Departemen Pendidikan Formal',
                'acronym' => 'DPF',
                'description' => 'Mengatur pendidikan formal santri',
            ],
            [
                'code' => 'DEPT_003',
                'name' => 'Departemen Keamanan dan Ketertiban',
                'acronym' => 'KAMTIB',
                'description' => 'Menjaga keamanan dan ketertiban pondok',
            ],
            [
                'code' => 'DEPT_004',
                'name' => 'Departemen Pengajian Kitab dan SKIA',
                'acronym' => 'TAZKIA',
                'description' => 'Mengatur ibadah dan kegiatan SKIA',
            ],
            [
                'code' => 'DEPT_005',
                'name' => 'Departemen Pengajian Al-Qur\'an dan Kitab',
                'acronym' => 'DEPAK',
                'description' => 'Mengatur pengajian Al-Qur\'an dan kitab kuning',
            ],
            [
                'code' => 'DEPT_006',
                'name' => 'Departemen Madrasah Diniyah',
                'acronym' => 'MADAL',
                'description' => 'Mengatur kegiatan Madrasah Diniyah',
            ],
            [
                'code' => 'DEPT_007',
                'name' => 'Perizinan',
                'acronym' => 'PERIZINAN',
                'description' => 'Mengatur validasi dan perizinan kepulangan santri',
                'type' => 'unit',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['code' => $dept['code']],
                $dept
            );
        }
    }
}

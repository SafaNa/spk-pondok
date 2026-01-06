<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departemen;
use Illuminate\Support\Str;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departemen = [
            [
                'id' => Str::uuid(),
                'kode_departemen' => 'DEPT_001',
                'nama_departemen' => 'Pendidikan & Pengajaran Madrasan Diniyah Annuqayah Latee II',
                'singkatan' => 'PENDIDIKAN',
                'keterangan' => 'Mengatur pendidikan dan pengajaran di Madrasan Diniyah',
            ],
            [
                'id' => Str::uuid(),
                'kode_departemen' => 'DEPT_002',
                'nama_departemen' => 'Pembinaan Akhlak',
                'singkatan' => 'AKHLAK',
                'keterangan' => 'Membina akhlak dan perilaku santri',
            ],
            [
                'id' => Str::uuid(),
                'kode_departemen' => 'DEPT_003',
                'nama_departemen' => 'Pendidikan Formal',
                'singkatan' => 'FORMAL',
                'keterangan' => 'Mengatur pendidikan formal santri',
            ],
            [
                'id' => Str::uuid(),
                'kode_departemen' => 'DEPT_004',
                'nama_departemen' => 'Keamanan & Ketertiban',
                'singkatan' => 'KEAMANAN',
                'keterangan' => 'Menjaga keamanan dan ketertiban pondok',
            ],
            [
                'id' => Str::uuid(),
                'kode_departemen' => 'DEPT_005',
                'nama_departemen' => 'Peribadatan dan SKIA',
                'singkatan' => 'PERIBADATAN',
                'keterangan' => 'Mengatur ibadah dan kegiatan SKIA',
            ],
            [
                'id' => Str::uuid(),
                'kode_departemen' => 'DEPT_006',
                'nama_departemen' => 'Pengajian Al-Qur\'an dan Kitab',
                'singkatan' => 'PENGAJIAN',
                'keterangan' => 'Mengatur pengajian Al-Qur\'an dan kitab kuning',
            ],
        ];

        foreach ($departemen as $dept) {
            Departemen::create($dept);
        }
    }
}

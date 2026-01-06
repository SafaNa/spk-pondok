<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPelanggaran;
use Illuminate\Support\Str;

class KategoriPelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'id' => Str::uuid(),
                'nama_kategori' => 'Ringan',
                'kode_kategori' => 'R',
                'bobot_poin' => 1,
            ],
            [
                'id' => Str::uuid(),
                'nama_kategori' => 'Sedang',
                'kode_kategori' => 'S',
                'bobot_poin' => 3,
            ],
            [
                'id' => Str::uuid(),
                'nama_kategori' => 'Berat',
                'kode_kategori' => 'B',
                'bobot_poin' => 5,
            ],
        ];

        foreach ($kategori as $kat) {
            KategoriPelanggaran::create($kat);
        }
    }
}

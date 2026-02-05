<?php

namespace Database\Seeders;

use App\Models\Master\MemorizationType;
use Illuminate\Database\Seeder;

class MemorizationTypeSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data to avoid duplicates since we are changing the structure
        MemorizationType::truncate();

        $data = [
            // MTS
            // 1 Hari
            ['education_level' => 'MTS', 'day' => 1, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MTS', 'day' => 1, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (I) (lihat SKIA)'],

            // 2 Hari
            ['education_level' => 'MTS', 'day' => 2, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MTS', 'day' => 2, 'target_description' => 'Doa agar diberi kepahaman dan cepat Hafal (I)'],
            ['education_level' => 'MTS', 'day' => 2, 'target_description' => 'Doa agar diberi kepahaman dan cepat Hafal (II)'],

            // 3 Hari
            ['education_level' => 'MTS', 'day' => 3, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MTS', 'day' => 3, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (I)'],
            ['education_level' => 'MTS', 'day' => 3, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (II)'],
            ['education_level' => 'MTS', 'day' => 3, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari)'],

            // 4 Hari
            ['education_level' => 'MTS', 'day' => 4, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MTS', 'day' => 4, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (I)'],
            ['education_level' => 'MTS', 'day' => 4, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (II)'],
            ['education_level' => 'MTS', 'day' => 4, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari)'],
            ['education_level' => 'MTS', 'day' => 4, 'target_description' => 'Doa Setelah Dhuha'],

            // 5 Hari
            ['education_level' => 'MTS', 'day' => 5, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MTS', 'day' => 5, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (I)'],
            ['education_level' => 'MTS', 'day' => 5, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (II)'],
            ['education_level' => 'MTS', 'day' => 5, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari)'],
            ['education_level' => 'MTS', 'day' => 5, 'target_description' => 'Doa Setelah Dhuha'],
            ['education_level' => 'MTS', 'day' => 5, 'target_description' => 'Doa Setelah Shalat Fardhu'],

            // 6 Hari
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (I)'],
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa agar diberi Kepahaman dan Cepat Hafal (II)'],
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari)'],
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa Setelah Dhuha'],
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa Setelah Shalat Fardhu'],
            ['education_level' => 'MTS', 'day' => 6, 'target_description' => 'Doa Khatmil Qur’an'],

            // MA
            // 1 Hari
            ['education_level' => 'MA', 'day' => 1, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'MA', 'day' => 1, 'target_description' => 'Doa Setelah Shalat Witir'],

            // 2 Hari
            ['education_level' => 'MA', 'day' => 2, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'MA', 'day' => 2, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'MA', 'day' => 2, 'target_description' => 'Doa Awal Tahun'],

            // 3 Hari
            ['education_level' => 'MA', 'day' => 3, 'target_description' => 'Doa setelah Surah Yasin'],
            ['education_level' => 'MA', 'day' => 3, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'MA', 'day' => 3, 'target_description' => 'Doa awal Tahun'],
            ['education_level' => 'MA', 'day' => 3, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari – Cetakan Baru)'],

            // 4 Hari
            ['education_level' => 'MA', 'day' => 4, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'MA', 'day' => 4, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'MA', 'day' => 4, 'target_description' => 'Doa Awal Tahun'],
            ['education_level' => 'MA', 'day' => 4, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari – Cetakan Baru)'],
            ['education_level' => 'MA', 'day' => 4, 'target_description' => 'Doa Setelah Shalat Tahajud'],

            // 5 Hari
            ['education_level' => 'MA', 'day' => 5, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'MA', 'day' => 5, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'MA', 'day' => 5, 'target_description' => 'Doa Awal Tahun'],
            ['education_level' => 'MA', 'day' => 5, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari – Cetakan Baru)'],
            ['education_level' => 'MA', 'day' => 5, 'target_description' => 'Doa Setelah Tahajud'],
            ['education_level' => 'MA', 'day' => 5, 'target_description' => 'Doa Setelah Tarawih'],

            // 6 Hari
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Awal Tahun'],
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Setelah Belajar (lihat Kumpulan Doa Sehari-hari – Cetakan Baru)'],
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Setelah Tahajud'],
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Setelah Tarawih'],
            ['education_level' => 'MA', 'day' => 6, 'target_description' => 'Doa Nishfu Sya’ban'],

            // PT
            // 1 Hari
            ['education_level' => 'PT', 'day' => 1, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'PT', 'day' => 1, 'target_description' => 'Doa Setelah Shalat Hajat dan Tarawih'],

            // 2 Hari
            ['education_level' => 'PT', 'day' => 2, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'PT', 'day' => 2, 'target_description' => 'Doa Setelah Shalat Hajat dan Tarawih'],
            ['education_level' => 'PT', 'day' => 2, 'target_description' => 'Doa Setelah Shalat Witir'],

            // 3 Hari
            ['education_level' => 'PT', 'day' => 3, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'PT', 'day' => 3, 'target_description' => 'Doa Setelah Shalat Hajat dan Tarawih'],
            ['education_level' => 'PT', 'day' => 3, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'PT', 'day' => 3, 'target_description' => 'Doa Khatmil Qur’an'],

            // 4 Hari
            ['education_level' => 'PT', 'day' => 4, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'PT', 'day' => 4, 'target_description' => 'Doa Setelah Shalat Hajat dan Tarawih'],
            ['education_level' => 'PT', 'day' => 4, 'target_description' => 'Doa Setelah Shalat Witir'],
            ['education_level' => 'PT', 'day' => 4, 'target_description' => 'Doa Khatmil Qur’an'],
            ['education_level' => 'PT', 'day' => 4, 'target_description' => 'Doa Setelah Shalat Istikharah'],

            // 5 Hari
            ['education_level' => 'PT', 'day' => 5, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'PT', 'day' => 5, 'target_description' => 'Doa Setelah Shalat Hajat'],
            ['education_level' => 'PT', 'day' => 5, 'target_description' => 'Doa Khatmil Qur’an'],
            ['education_level' => 'PT', 'day' => 5, 'target_description' => 'Doa Setelah Tarawih'],
            ['education_level' => 'PT', 'day' => 5, 'target_description' => 'Doa Bulan Asyuro (lihat Kumpulan Doa Sehari-hari – Cetakan Baru)'],

            // 6 Hari
            ['education_level' => 'PT', 'day' => 6, 'target_description' => 'Doa Setelah Surah Yasin'],
            ['education_level' => 'PT', 'day' => 6, 'target_description' => 'Doa Setelah Shalat Hajat'],
            ['education_level' => 'PT', 'day' => 6, 'target_description' => 'Doa Khatmil Qur’an'],
            ['education_level' => 'PT', 'day' => 6, 'target_description' => 'Doa Awal Tahun'],
            ['education_level' => 'PT', 'day' => 6, 'target_description' => 'Doa Bulan Asyuro (lihat Kumpulan Doa Sehari-hari – Cetakan Baru)'],
            ['education_level' => 'PT', 'day' => 6, 'target_description' => 'Doa Nishfu Sya’ban'],
        ];

        foreach ($data as $item) {
            MemorizationType::create($item);
        }
    }
}

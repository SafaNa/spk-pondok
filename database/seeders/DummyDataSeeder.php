<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Santri;
use App\Models\Periode;
use App\Models\RiwayatHitung;
use App\Models\Kriteria;
use App\Models\Penilaian;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Create 15 Periods (Triggers Pagination > 10)
        $periodes = [];
        for ($i = 0; $i < 15; $i++) {
            $periodes[] = Periode::create([
                'nama' => 'Periode Dummy ' . $faker->monthName . ' ' . (2020 + $i),
                'keterangan' => 'Periode generate dummy ' . $i,
                'is_active' => false
            ]);
        }
        $this->command->info('Created 15 Dummy Periods');

        // 2. Create 50 Santri (Triggers Pagination > 10, Rekomendasi > 20)
        $santris = [];
        for ($i = 0; $i < 50; $i++) {
            $santris[] = Santri::create([
                'nis' => 'DUMMY' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date,
                'alamat' => $faker->address,
                'nama_ortu' => $faker->name,
                'no_hp_ortu' => $faker->phoneNumber,
                'status' => 'aktif',
                // Random score for recommendation
                'nilai_akhir' => $faker->randomFloat(2, 0.1, 1.0)
            ]);
        }
        $this->command->info('Created 50 Dummy Santri with random scores');

        // 3. Create ~100 History Records with Penilaian (Triggers History Pagination > 20)
        $kriterias = \App\Models\Kriteria::with('subkriteria')->get();

        foreach ($periodes as $periode) {
            // Select random 5-10 santri for each period
            $selectedSantri = $faker->randomElements($santris, rand(5, 10));

            foreach ($selectedSantri as $s) {
                // Generate random score
                $nilai_akhir = $faker->randomFloat(2, 0.1, 1.0);

                // Create History
                RiwayatHitung::create([
                    'santri_id' => $s->id,
                    'periode_id' => $periode->id,
                    'nilai_akhir' => $nilai_akhir
                ]);

                // Create Penilaian (Assessment) for this period
                foreach ($kriterias as $k) {
                    $randomSub = $k->subkriteria->random();
                    if ($randomSub) {
                        \App\Models\Penilaian::create([
                            'santri_id' => $s->id,
                            'periode_id' => $periode->id,
                            'kriteria_id' => $k->id,
                            'subkriteria_id' => $randomSub->id,
                            'nilai' => $randomSub->nilai
                        ]);
                    }
                }
            }
        }
        $this->command->info('Created ~100 History Records with linked Penilaian data');
    }
}

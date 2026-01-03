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

        // 1. Create 3 Periods
        $periodes = [];
        for ($i = 0; $i < 3; $i++) {
            $periodes[] = Periode::create([
                'nama' => 'Periode Dummy ' . $faker->monthName . ' ' . (2023 + $i),
                'keterangan' => 'Periode generate dummy ' . $i,
                'is_active' => ($i === 2) // Set the last one as active
            ]);
        }
        $this->command->info('Created 3 Dummy Periods');

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

        // 3. Create History Records with Penilaian
        $kriterias = \App\Models\Kriteria::with('subkriteria')->get();

        // Pre-calculate Min/Max for SAW
        $criteriaMinMax = [];
        foreach ($kriterias as $k) {
            $criteriaMinMax[$k->id] = [
                'min' => $k->subkriteria->min('nilai'),
                'max' => $k->subkriteria->max('nilai'),
                'jenis' => $k->jenis,
                'bobot' => $k->bobot
            ];
        }

        $totalBobot = $kriterias->sum('bobot');

        foreach ($periodes as $periode) {
            // Select random 5-10 santri for each period
            $selectedSantri = $faker->randomElements($santris, rand(5, 10));

            foreach ($selectedSantri as $s) {
                // 1. Generate Penilaian First
                $santriNilai = []; // Store nilai per kriteria for calculation

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
                        $santriNilai[$k->id] = $randomSub->nilai;
                    }
                }

                // 2. Calculate Final Score (SAW Logic)
                $nilai_akhir = 0;
                foreach ($kriterias as $k) {
                    if (isset($santriNilai[$k->id])) {
                        $nilai = $santriNilai[$k->id];
                        $min = $criteriaMinMax[$k->id]['min'];
                        $max = $criteriaMinMax[$k->id]['max'];
                        $jenis = $criteriaMinMax[$k->id]['jenis'];
                        $bobot = $criteriaMinMax[$k->id]['bobot'];

                        $bobotTernormalisasi = $bobot / $totalBobot;
                        $normalisasi = 0;

                        if ($jenis == 'benefit') {
                            $normalisasi = $max > 0 ? $nilai / $max : 0;
                        } else {
                            $normalisasi = $nilai > 0 ? $min / $nilai : 0;
                        }

                        $nilai_akhir += $normalisasi * $bobotTernormalisasi;
                    }
                }

                // 3. Create History with Calculated Score
                RiwayatHitung::create([
                    'santri_id' => $s->id,
                    'periode_id' => $periode->id,
                    'nilai_akhir' => $nilai_akhir
                ]);

                // 4. Update Santri with latest score (from this loop)
                $s->update(['nilai_akhir' => $nilai_akhir]);
            }
        }
        $this->command->info('Created History Records with valid SAW calculations');
    }
}

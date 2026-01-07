<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Santri;
use App\Models\Periode;
use App\Models\RiwayatHitung;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Models\Penilaian;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 0. Clean up Kriteria first to avoid duplicates/conflicts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Penilaian::truncate();
        RiwayatHitung::truncate();
        \App\Models\RiwayatPelanggaran::truncate();
        Subkriteria::truncate();
        Kriteria::truncate();
        Santri::truncate();
        Periode::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create 3 Specific Criteria
        $criteriaData = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Status Penyelesaian Sanksi',
                'bobot' => 40,
                'jenis' => 'benefit', // Higher is better (Selesai = 100)
                'keterangan' => 'Status ketuntasan pelanggaran',
                'subs' => [
                    ['nama' => 'Selesai / Tidak Ada Masalah', 'nilai' => 100],
                    ['nama' => 'Belum Selesai (Ada Tanggungan)', 'nilai' => 10], // Penalized heavily
                ]
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Status Pembayaran SPP',
                'bobot' => 30,
                'jenis' => 'benefit',
                'keterangan' => 'Kelancaran administrasi',
                'subs' => [
                    ['nama' => 'Lunas', 'nilai' => 100],
                    ['nama' => 'Belum Lunas', 'nilai' => 50],
                    ['nama' => 'Menunggak', 'nilai' => 10],
                ]
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Status Hafalan Wajib',
                'bobot' => 30,
                'jenis' => 'benefit',
                'keterangan' => 'Pencapaian target hafalan',
                'subs' => [
                    ['nama' => 'Lengkap (100%)', 'nilai' => 100],
                    ['nama' => 'Hampir Lengkap (>75%)', 'nilai' => 80],
                    ['nama' => 'Cukup (>50%)', 'nilai' => 60],
                    ['nama' => 'Kurang (<50%)', 'nilai' => 30],
                ]
            ]
        ];

        foreach ($criteriaData as $c) {
            $kriteria = Kriteria::create([
                'id' => Str::uuid(),
                'kode_kriteria' => $c['kode_kriteria'],
                'nama_kriteria' => $c['nama_kriteria'],
                'bobot' => $c['bobot'],
                'jenis' => $c['jenis'],
                'keterangan' => $c['keterangan']
            ]);

            foreach ($c['subs'] as $sub) {
                Subkriteria::create([
                    'id' => Str::uuid(),
                    'kriteria_id' => $kriteria->id,
                    'nama_subkriteria' => $sub['nama'],
                    'nilai' => $sub['nilai']
                ]);
            }
        }
        $this->command->info('Created 3 Mandated Criteria (C1, C2, C3)');

        // 2. Create 3 Periods
        $periodes = [];
        for ($i = 0; $i < 3; $i++) {
            $periodes[] = Periode::create([
                'nama' => 'Periode ' . $faker->monthName . ' ' . (2025),
                'keterangan' => 'Periode generate dummy ' . $i,
                'is_active' => ($i === 2) // Set the last one as active
            ]);
        }
        $this->command->info('Created 3 Dummy Periods');

        // 3. Create 50 Santri with Specific Status Data
        $santris = [];
        for ($i = 0; $i < 50; $i++) {
            // Randomly assign status
            $statusSpp = $faker->randomElement(['lunas', 'belum_lunas', 'menunggak']);
            $statusHafalan = $faker->randomElement(['lengkap', 'belum_lengkap']);
            $targetHafalan = 100;
            $hafalanTercapai = $statusHafalan == 'lengkap' ? 100 : $faker->numberBetween(20, 90);

            $santris[] = Santri::create([
                'nis' => 'SANTRI' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nama' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date,
                'alamat' => $faker->address,
                'nama_ortu' => $faker->name,
                'no_hp_ortu' => $faker->phoneNumber,
                'status' => 'aktif',
                // Populate required fields for automation
                'status_spp' => $statusSpp,
                'status_hafalan' => $statusHafalan,
                'target_hafalan' => $targetHafalan,
                'jumlah_hafalan_tercapai' => $hafalanTercapai,
                'nilai_akhir' => 0
            ]);
        }
        $this->command->info('Created 50 Santri with SPP & Hafalan Data');

        // 4. Create Assessments (Mocking the Automation)
        // In reality, the Controller will do this. But for "History", we simulate it.
        $activePeriode = end($periodes); // only populate for active for now to be fast
        $kriterias = Kriteria::all();

        foreach ($santris as $s) {
            // Simulate C1: Status Sanksi
            // 80% chance of being Clean/Selesai
            $isClean = $faker->boolean(80);
            $valC1 = $isClean ? 100 : 10;

            $subC1 = Subkriteria::where('kriteria_id', $kriterias->where('kode_kriteria', 'C1')->first()->id)
                ->where('nilai', $valC1)->first();

            if ($subC1) {
                Penilaian::create([
                    'id' => Str::uuid(),
                    'santri_id' => $s->id,
                    'kriteria_id' => $subC1->kriteria_id,
                    'subkriteria_id' => $subC1->id,
                    'periode_id' => $activePeriode->id,
                    'nilai' => $subC1->nilai
                ]);
            }

            // Map C2: SPP
            $valC2 = match ($s->status_spp) {
                'lunas' => 100,
                'belum_lunas' => 50,
                'menunggak' => 10,
            };
            $subC2 = Subkriteria::where('kriteria_id', $kriterias->where('kode_kriteria', 'C2')->first()->id)
                ->where('nilai', $valC2)->first();

            if ($subC2) {
                Penilaian::create([
                    'id' => Str::uuid(),
                    'santri_id' => $s->id,
                    'kriteria_id' => $subC2->kriteria_id,
                    'subkriteria_id' => $subC2->id,
                    'periode_id' => $activePeriode->id,
                    'nilai' => $subC2->nilai
                ]);
            }

            // Map C3: Hafalan
            $ratio = $s->jumlah_hafalan_tercapai / $s->target_hafalan;
            $valC3 = 30; // default < 50%
            if ($s->status_hafalan == 'lengkap' || $ratio == 1)
                $valC3 = 100;
            elseif ($ratio > 0.75)
                $valC3 = 80;
            elseif ($ratio > 0.50)
                $valC3 = 60;

            $subC3 = Subkriteria::where('kriteria_id', $kriterias->where('kode_kriteria', 'C3')->first()->id)
                ->where('nilai', $valC3)->first();

            if ($subC3) {
                Penilaian::create([
                    'id' => Str::uuid(),
                    'santri_id' => $s->id,
                    'kriteria_id' => $subC3->kriteria_id,
                    'subkriteria_id' => $subC3->id,
                    'periode_id' => $activePeriode->id,
                    'nilai' => $subC3->nilai
                ]);
            }
        }
    }
}

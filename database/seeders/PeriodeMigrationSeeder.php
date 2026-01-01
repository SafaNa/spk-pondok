<?php

namespace Database\Seeders;

use App\Models\Periode;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;

class PeriodeMigrationSeeder extends Seeder
{
    public function run()
    {
        // Create initial period if none exists
        if (Periode::count() == 0) {
            $periode = Periode::create([
                'nama' => 'Periode Awal',
                'is_active' => true,
                'keterangan' => 'Periode otomatis dari migrasi data lama'
            ]);

            // Update existing assessments to belong to this period
            Penilaian::whereNull('periode_id')->update(['periode_id' => $periode->id]);

            $this->command->info('Periode Awal created and Penilaian migrated.');
        } else {
            $periode = Periode::first();
            $this->command->info('Periodes already exist. Using existing period for migration.');
        }

        // Migrate Santri nilai_akhir to RiwayatHitung
        $santris = \App\Models\Santri::whereNotNull('nilai_akhir')->get();
        $count = 0;
        foreach ($santris as $santri) {
            \App\Models\RiwayatHitung::updateOrCreate(
                [
                    'santri_id' => $santri->id,
                    'periode_id' => $periode->id
                ],
                [
                    'nilai_akhir' => $santri->nilai_akhir
                ]
            );
            $count++;
        }

        $this->command->info("RiwayatHitung migrated for $count santris.");
    }
}

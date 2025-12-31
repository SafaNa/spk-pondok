<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $kriteria = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Kedisiplinan',
                'bobot' => 30,
                'jenis' => 'benefit',
                'keterangan' => 'Tingkat kedisiplinan santri dalam mengikuti kegiatan pondok'
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Prestasi Akademik',
                'bobot' => 25,
                'jenis' => 'benefit',
                'keterangan' => 'Nilai rata-rata raport santri'
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Hafalan Al-Quran',
                'bobot' => 20,
                'jenis' => 'benefit',
                'keterangan' => 'Jumlah hafalan Al-Quran santri'
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Pelanggaran',
                'bobot' => 25,
                'jenis' => 'cost',
                'keterangan' => 'Jumlah pelanggaran yang dilakukan santri'
            ],
        ];

        foreach ($kriteria as $k) {
            $kriteria = Kriteria::create($k);

            // Create subkriteria
            if ($k['kode_kriteria'] == 'C1') {
                $subkriteria = [
                    ['nama_subkriteria' => 'Sangat Disiplin', 'nilai' => 5, 'keterangan' => 'Selalu mengikuti kegiatan tepat waktu tanpa absen'],
                    ['nama_subkriteria' => 'Disiplin', 'nilai' => 4, 'keterangan' => 'Mengikuti kegiatan dengan baik, jarang terlambat'],
                    ['nama_subkriteria' => 'Cukup Disiplin', 'nilai' => 3, 'keterangan' => 'Kadang terlambat atau absen dengan alasan'],
                    ['nama_subkriteria' => 'Kurang Disiplin', 'nilai' => 2, 'keterangan' => 'Sering terlambat atau absen tanpa alasan jelas'],
                    ['nama_subkriteria' => 'Tidak Disiplin', 'nilai' => 1, 'keterangan' => 'Sering membolos dan tidak mengikuti aturan pondok'],
                ];
            } elseif ($k['kode_kriteria'] == 'C2') {
                $subkriteria = [
                    ['nama_subkriteria' => 'Sangat Baik (90-100)', 'nilai' => 5, 'keterangan' => 'Rata-rata nilai rapor 90 sampai 100'],
                    ['nama_subkriteria' => 'Baik (80-89)', 'nilai' => 4, 'keterangan' => 'Rata-rata nilai rapor 80 sampai 89'],
                    ['nama_subkriteria' => 'Cukup (70-79)', 'nilai' => 3, 'keterangan' => 'Rata-rata nilai rapor 70 sampai 79'],
                    ['nama_subkriteria' => 'Kurang (60-69)', 'nilai' => 2, 'keterangan' => 'Rata-rata nilai rapor 60 sampai 69'],
                    ['nama_subkriteria' => 'Sangat Kurang (<60)', 'nilai' => 1, 'keterangan' => 'Rata-rata nilai rapor di bawah 60'],
                ];
            } elseif ($k['kode_kriteria'] == 'C3') {
                $subkriteria = [
                    ['nama_subkriteria' => '> 5 Juz', 'nilai' => 5, 'keterangan' => 'Memiliki hafalan lancar lebih dari 5 Juz'],
                    ['nama_subkriteria' => '3-5 Juz', 'nilai' => 4, 'keterangan' => 'Memiliki hafalan 3 sampai 5 Juz'],
                    ['nama_subkriteria' => '1-2 Juz', 'nilai' => 3, 'keterangan' => 'Memiliki hafalan 1 sampai 2 Juz'],
                    ['nama_subkriteria' => 'Sedang Menghafal', 'nilai' => 2, 'keterangan' => 'Masih dalam proses menghafal Juz 30/Juz 1'],
                    ['nama_subkriteria' => 'Belum Mulai', 'nilai' => 1, 'keterangan' => 'Belum memiliki hafalan Al-Quran'],
                ];
            } else {
                $subkriteria = [
                    ['nama_subkriteria' => 'Tidak Pernah', 'nilai' => 1, 'keterangan' => 'Tidak memiliki catatan pelanggaran sama sekali'],
                    ['nama_subkriteria' => '1-2 Kali', 'nilai' => 2, 'keterangan' => 'Melakukan pelanggaran ringan 1-2 kali'],
                    ['nama_subkriteria' => '3-5 Kali', 'nilai' => 3, 'keterangan' => 'Melakukan pelanggaran sedang atau ringan berulang'],
                    ['nama_subkriteria' => '5-10 Kali', 'nilai' => 4, 'keterangan' => 'Sering melakukan pelanggaran'],
                    ['nama_subkriteria' => '> 10 Kali', 'nilai' => 5, 'keterangan' => 'Sangat sering melanggar aturan berat'],
                ];
            }

            foreach ($subkriteria as $sk) {
                $kriteria->subkriteria()->create($sk);
            }
        }
    }
}
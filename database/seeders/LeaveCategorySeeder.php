<?php

namespace Database\Seeders;

use App\Models\Licensing\LeaveCategory;
use App\Models\Licensing\LeaveReason;
use Illuminate\Database\Seeder;

class LeaveCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'                => 'Kesehatan',
                'max_duration'        => 'Maksimal 2 hari 2 malam (dapat diperpanjang dengan Surat Keterangan Dokter)',
                'is_fixed_duration'   => true,
                'duration_days'       => 2,
                'notes'               => 'Dapat diprioritaskan tanpa validasi lengkap dalam kondisi darurat',
                'order'               => 1,
                'reasons'             => [
                    ['reason' => 'Santri sakit (dengan rekomendasi Dept. Kesehatan)', 'can_skip' => true],
                    ['reason' => 'Pemeriksaan kesehatan', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Keluarga Inti',
                'max_duration'        => 'Menyesuaikan kebutuhan dan persetujuan pengurus',
                'notes'               => 'Khusus kondisi sakit atau wafat dapat diproses tanpa validasi lengkap',
                'order'               => 2,
                'reasons'             => [
                    ['reason' => 'Orang tua sakit/wafat', 'can_skip' => true],
                    ['reason' => 'Saudara kandung sakit/wafat', 'can_skip' => true],
                    ['reason' => 'Orang tua/saudara menikah', 'can_skip' => false],
                    ['reason' => 'Orang tua/saudara umrah (pertama)', 'can_skip' => false],
                    ['reason' => 'Orang tua/saudara haji', 'can_skip' => false],
                    ['reason' => 'Wisuda S1/S2/S3 keluarga inti', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Keluarga (Non-Inti)',
                'max_duration'        => 'Menyesuaikan kebutuhan dan persetujuan pengurus',
                'notes'               => 'Khusus kondisi sakit atau kifayah (kematian) dapat diprioritaskan',
                'order'               => 3,
                'reasons'             => [
                    ['reason' => 'Kakek/nenek sakit', 'can_skip' => true],
                    ['reason' => 'Kifayah: buyut, kakek/nenek, paman/bibi, ponakan', 'can_skip' => true],
                    ['reason' => 'Walimatul \'ursy', 'can_skip' => false],
                    ['reason' => 'Umrah/haji keluarga', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Ibadah Haji',
                'max_duration'        => 'Maksimal ±45 hari (termasuk persiapan dan kepulangan)',
                'is_fixed_duration'   => true,
                'duration_days'       => 45,
                'notes'               => 'Mengikuti prosedur lengkap',
                'order'               => 4,
                'reasons'             => [
                    ['reason' => 'Kepulangan untuk pelaksanaan ibadah haji', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Akademik',
                'max_duration'        => 'Menyesuaikan kebutuhan dan bukti administratif',
                'notes'               => 'Mengikuti prosedur lengkap',
                'order'               => 5,
                'reasons'             => [
                    ['reason' => 'Tugas penelitian', 'can_skip' => false],
                    ['reason' => 'Seleksi pendidikan lanjutan', 'can_skip' => false],
                    ['reason' => 'Pengurusan beasiswa', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Pertunangan',
                'max_duration'        => 'Menyesuaikan kebutuhan dan persetujuan pengurus',
                'notes'               => 'Mengikuti prosedur lengkap',
                'order'               => 6,
                'reasons'             => [
                    ['reason' => 'Ater kembheng (lamaran)', 'can_skip' => false],
                    ['reason' => 'Tongngepan (balasan)', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Pernikahan',
                'max_duration'        => 'Menyesuaikan kebutuhan dan persetujuan pengurus',
                'notes'               => 'Mengikuti prosedur lengkap',
                'order'               => 7,
                'reasons'             => [
                    ['reason' => 'Persiapan pernikahan', 'can_skip' => false],
                ],
            ],
            [
                'name'                => 'Administratif',
                'max_duration'        => 'Menyesuaikan kebutuhan dan jadwal pelayanan',
                'notes'               => 'Mengikuti prosedur lengkap',
                'order'               => 8,
                'reasons'             => [
                    ['reason' => 'Pembuatan KTP', 'can_skip' => false],
                ],
            ],
        ];

        foreach ($data as $item) {
            $category = LeaveCategory::create([
                'name'                => $item['name'],
                'max_duration'        => $item['max_duration'],
                'is_fixed_duration'   => $item['is_fixed_duration'] ?? false,
                'duration_days'       => $item['duration_days'] ?? null,
                'notes'               => $item['notes'],
                'order'               => $item['order'],
            ]);

            foreach ($item['reasons'] as $i => $reasonData) {
                LeaveReason::create([
                    'leave_category_id'   => $category->id,
                    'reason'              => $reasonData['reason'],
                    'can_skip_validation' => $reasonData['can_skip'],
                    'order'               => $i,
                ]);
            }
        }
    }
}

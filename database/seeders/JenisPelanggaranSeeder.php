<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPelanggaran;
use App\Models\Departemen;
use App\Models\KategoriPelanggaran;
use Illuminate\Support\Str;

class JenisPelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get departemen and kategori references
        $pendidikan = Departemen::where('kode_departemen', 'DEPT_001')->first();
        $akhlak = Departemen::where('kode_departemen', 'DEPT_002')->first();
        $formal = Departemen::where('kode_departemen', 'DEPT_003')->first();
        $keamanan = Departemen::where('kode_departemen', 'DEPT_004')->first();
        $peribadatan = Departemen::where('kode_departemen', 'DEPT_005')->first();
        $pengajian = Departemen::where('kode_departemen', 'DEPT_006')->first();

        $ringan = KategoriPelanggaran::where('kode_kategori', 'R')->first();
        $sedang = KategoriPelanggaran::where('kode_kategori', 'S')->first();
        $berat = KategoriPelanggaran::where('kode_kategori', 'B')->first();

        $violations = [
            // DEPT_001: Pendidikan & Pengajaran
            [
                'kode_pelanggaran' => 'PEND-001',
                'nama_pelanggaran' => 'Tidak mengikuti pelajaran tanpa izin',
                'deskripsi' => 'Bolos pelajaran Madrasan Diniyah',
                'sanksi_default' => 'Tugas tambahan dan hafalan surat pendek',
                'departemen_id' => $pendidikan->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],
            [
                'kode_pelanggaran' => 'PEND-002',
                'nama_pelanggaran' => 'Terlambat masuk kelas',
                'deskripsi' => 'Datang terlambat ke kelas lebih dari 15 menit',
                'sanksi_default' => 'Berdiri di depan kelas selama 1 jam pelajaran',
                'departemen_id' => $pendidikan->id,
                'kategori_pelanggaran_id' => $ringan->id,
            ],
            [
                'kode_pelanggaran' => 'PEND-003',
                'nama_pelanggaran' => 'Tidak mengerjakan tugas berulang kali',
                'deskripsi' => 'Lebih dari 3 kali tidak mengerjakan tugas',
                'sanksi_default' => 'Mengerjakan tugas 2x lipat dan hafalan hadits 5 buah',
                'departemen_id' => $pendidikan->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],

            // DEPT_002: Pembinaan Akhlak
            [
                'kode_pelanggaran' => 'AKH-001',
                'nama_pelanggaran' => 'Berbicara kasar kepada sesama santri',
                'deskripsi' => 'Menggunakan kata-kata tidak sopan',
                'sanksi_default' => 'Hafalan hadits tentang akhlak 10 buah',
                'departemen_id' => $akhlak->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],
            [
                'kode_pelanggaran' => 'AKH-002',
                'nama_pelanggaran' => 'Tidak menjaga kebersihan lingkungan',
                'deskripsi' => 'Membuang sampah sembarangan',
                'sanksi_default' => 'Piket kebersihan selama 1 minggu',
                'departemen_id' => $akhlak->id,
                'kategori_pelanggaran_id' => $ringan->id,
            ],
            [
                'kode_pelanggaran' => 'AKH-003',
                'nama_pelanggaran' => 'Berkelahi dengan santri lain',
                'deskripsi' => 'Terlibat perkelahian fisik',
                'sanksi_default' => 'Hafalan 1 Juz dan pembinaan khusus',
                'departemen_id' => $akhlak->id,
                'kategori_pelanggaran_id' => $berat->id,
            ],

            // DEPT_003: Pendidikan Formal
            [
                'kode_pelanggaran' => 'FORM-001',
                'nama_pelanggaran' => 'Tidak mengikuti ujian sekolah formal',
                'deskripsi' => 'Absen saat ujian tanpa alasan jelas',
                'sanksi_default' => 'Ujian susulan dan tugas tambahan',
                'departemen_id' => $formal->id,
                'kategori_pelanggaran_id' => $berat->id,
            ],
            [
                'kode_pelanggaran' => 'FORM-002',
                'nama_pelanggaran' => 'Terlambat masuk sekolah',
                'deskripsi' => 'Datang terlambat ke sekolah formal',
                'sanksi_default' => 'Membersihkan halaman sekolah',
                'departemen_id' => $formal->id,
                'kategori_pelanggaran_id' => $ringan->id,
            ],
            [
                'kode_pelanggaran' => 'FORM-003',
                'nama_pelanggaran' => 'Mencontek saat ujian',
                'deskripsi' => 'Ketahuan menyontek',
                'sanksi_default' => 'Nilai ujian 0 dan hafalan hadits 15 buah',
                'departemen_id' => $formal->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],

            // DEPT_004: Keamanan & Ketertiban
            [
                'kode_pelanggaran' => 'KMN-001',
                'nama_pelanggaran' => 'Keluar pondok tanpa izin',
                'deskripsi' => 'Meninggalkan pondok tanpa seizin pengurus',
                'sanksi_default' => 'Hafalan 1 Juz dan tidak boleh pulang 1 periode',
                'departemen_id' => $keamanan->id,
                'kategori_pelanggaran_id' => $berat->id,
            ],
            [
                'kode_pelanggaran' => 'KMN-002',
                'nama_pelanggaran' => 'Membawa barang terlarang',
                'deskripsi' => 'Membawa rokok, HP, atau barang terlarang lainnya',
                'sanksi_default' => 'Barang disita dan hafalan 1/2 Juz',
                'departemen_id' => $keamanan->id,
                'kategori_pelanggaran_id' => $berat->id,
            ],
            [
                'kode_pelanggaran' => 'KMN-003',
                'nama_pelanggaran' => 'Berada di luar kamar setelah jam malam',
                'deskripsi' => 'Keluar kamar setelah jam istirahat',
                'sanksi_default' => 'Hafalan surat pendek 5 buah',
                'departemen_id' => $keamanan->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],
            [
                'kode_pelanggaran' => 'KMN-004',
                'nama_pelanggaran' => 'Tidak memakai seragam pondok',
                'deskripsi' => 'Tidak menggunakan seragam yang ditentukan',
                'sanksi_default' => 'Teguran dan tugas piket 3 hari',
                'departemen_id' => $keamanan->id,
                'kategori_pelanggaran_id' => $ringan->id,
            ],

            // DEPT_005: Peribadatan dan SKIA
            [
                'kode_pelanggaran' => 'IBD-001',
                'nama_pelanggaran' => 'Tidak mengikuti sholat berjamaah',
                'deskripsi' => 'Absen sholat berjamaah tanpa uzur',
                'sanksi_default' => 'Hafalan hadits tentang sholat 10 buah',
                'departemen_id' => $peribadatan->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],
            [
                'kode_pelanggaran' => 'IBD-002',
                'nama_pelanggaran' => 'Terlambat sholat berjamaah',
                'deskripsi' => 'Datang setelah rakaat pertama',
                'sanksi_default' => 'Tugas bersih masjid selama 3 hari',
                'departemen_id' => $peribadatan->id,
                'kategori_pelanggaran_id' => $ringan->id,
            ],
            [
                'kode_pelanggaran' => 'IBD-003',
                'nama_pelanggaran' => 'Tidak mengikuti kegiatan SKIA',
                'deskripsi' => 'Bolos kegiatan SKIA yang wajib',
                'sanksi_default' => 'Tugas tambahan dan hafalan',
                'departemen_id' => $peribadatan->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],

            // DEPT_006: Pengajian Al-Qur'an dan Kitab
            [
                'kode_pelanggaran' => 'KJI-001',
                'nama_pelanggaran' => 'Tidak mengikuti pengajian kitab',
                'deskripsi' => 'Bolos pengajian kitab kuning',
                'sanksi_default' => 'Hafalan materi yang tertinggal',
                'departemen_id' => $pengajian->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],
            [
                'kode_pelanggaran' => 'KJI-002',
                'nama_pelanggaran' => 'Tidak membawa kitab saat pengajian',
                'deskripsi' => 'Lupa atau tidak membawa kitab yang diwajibkan',
                'sanksi_default' => 'Menulis ulang materi pelajaran',
                'departemen_id' => $pengajian->id,
                'kategori_pelanggaran_id' => $ringan->id,
            ],
            [
                'kode_pelanggaran' => 'KJI-003',
                'nama_pelanggaran' => 'Tidak menyelesaikan target hafalan Al-Qur\'an',
                'deskripsi' => 'Gagal mencapai target hafalan yang ditentukan',
                'sanksi_default' => 'Setoran hafalan tambahan 2x lipat',
                'departemen_id' => $pengajian->id,
                'kategori_pelanggaran_id' => $sedang->id,
            ],
        ];

        foreach ($violations as $violation) {
            JenisPelanggaran::create([
                'id' => Str::uuid(),
                ...$violation
            ]);
        }
    }
}

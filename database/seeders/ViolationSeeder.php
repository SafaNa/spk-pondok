<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Violation\ViolationCategory;
use App\Models\Violation\ViolationType;
use App\Models\Master\Department;

class ViolationSeeder extends Seeder
{
    /**
     * Seed data pelanggaran santri P2AL II
     *
     * Sumber: tata-tertib.docx
     * Teks name diambil PERSIS dari dokumen tanpa perubahan.
     *
     * Tata Tertib dibagi 2:
     *   [pesantren] Tata Tertib Pondok Pesantren P2AL II
     *     - Bab I  : Kewajiban-kewajiban (18 item) → kode PP-KW-XX
     *     - Bab II : Larangan-larangan    (43 item) → kode PP-LR-XX
     *   [madrasah]  Tata Tertib Madrasah Diniah Annuqayah Latee II (MADAL)
     *     - Bab I  : Kewajiban-kewajiban  (6 item) → kode MDL-KW-XX
     *     - Bab II : Larangan-larangan   (11 item) → kode MDL-LR-XX
     *
     * Pemetaan departemen:
     *   KAMTIB   → KAMTIB
     *   DPA      → DPA
     *   TASKIA   → TASKIA
     *   DPF      → DPF
     *   MADAL    → MADAL
     *   PERPUS   → (Dihapus)
     *   BENDAHARA→ (Dihapus)
     *   KEBERSIHAN/PU → (Dihapus)
     */
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────────────
        // 1. KATEGORI PELANGGARAN
        // ─────────────────────────────────────────────────────────────────────
        $ringan = ViolationCategory::firstOrCreate(
            ['name' => 'Ringan'],
            ['points' => 1, 'description' => 'Pelanggaran ringan yang tidak berdampak besar, cukup diberi teguran atau sanksi ringan.']
        );
        $sedang = ViolationCategory::firstOrCreate(
            ['name' => 'Sedang'],
            ['points' => 3, 'description' => 'Pelanggaran sedang yang memerlukan perhatian dan sanksi yang lebih tegas.']
        );
        $berat = ViolationCategory::firstOrCreate(
            ['name' => 'Berat'],
            ['points' => 5, 'description' => 'Pelanggaran berat yang dapat merugikan pesantren, sesama santri, atau nama baik P2AL II.']
        );

        // ─────────────────────────────────────────────────────────────────────
        // 2. DEPARTEMEN
        // ─────────────────────────────────────────────────────────────────────

        $deptAkhlak      = Department::where('acronym', 'DPA')->firstOrFail();
        $deptFormal      = Department::where('acronym', 'DPF')->firstOrFail();
        $deptKeamanan    = Department::where('acronym', 'KAMTIB')->firstOrFail();
        $deptPeribadatan = Department::where('acronym', 'TASKIA')->firstOrFail();
        $deptDepak       = Department::where('acronym', 'DEPAK')->firstOrFail();
        $deptPerizinan   = Department::where('acronym', 'PERIZINAN')->firstOrFail();
        $deptMadal       = Department::where('acronym', 'MADAL')->firstOrFail();

        // ─────────────────────────────────────────────────────────────────────
        // 3. JENIS PELANGGARAN
        // ─────────────────────────────────────────────────────────────────────
        $types = [

            // =================================================================
            // TATA TERTIB PONDOK PESANTREN P2AL II  (ruleset = pesantren)
            // =================================================================

            // ── BAB I: KEWAJIBAN-KEWAJIBAN ────────────────────────────────────
            // Kategori (Bab IV): Berat = ayat 1 | Sedang = ayat 11, 16, 18 | Ringan = selebihnya

            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-KW-01',
                'name'                  => 'Menjaga nama baik P2AL II',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-02',
                'name'                  => 'Mengikuti seluruh kegiatan yang diadakan pengurus P2AL II',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-03',
                'name'                  => 'Menjaga seluruh kartu yang diterbitkan pengurus P2AL II',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptFormal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-04',
                'name'                  => 'Membelikan surat untuk anggota kamar yang berhalangan mengikuti KBM di satuan Pendidikan Annuqayah',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],

            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-06',
                'name'                  => 'Memakai busana sesuai tatanan kaidah yang berlaku di P2AL II',
                'default_sanction'      => 'Membersihkan lingkungan P2AL II.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptFormal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-07',
                'name'                  => 'Memakai sepatu dan/ atau kaos kaki panjang pada jam KBM formal',
                'default_sanction'      => 'Membaca karya tulis ilmiah.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptFormal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-08',
                'name'                  => 'Berangkat sekolah sesuai waktu yang telah ditentukan',
                'default_sanction'      => 'Membersihkan lingkungan P2AL II; Membaca karya tulis ilmiah.',
                'is_active'             => true,
            ],

            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-10',
                'name'                  => 'Melaporkan hal-hal yang merugikan pesantren kepada pengurus P2AL II',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-KW-11',
                'name'                  => 'Pulang dan kembali sesuai prosedur kepulangan',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-12',
                'name'                  => 'Kembali setelah libur sesuai batas waktu yang ditentukan',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-13',
                'name'                  => 'Menyerahkan surat keterangan menikah atau surat keterangan kepala desa bagi santri yang menikah',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-14',
                'name'                  => 'Melapor kepada pengurus atau pengasuh saat menerima tamu , beristirahat , atau berdiam lama',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],

            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptPeribadatan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-17',
                'name'                  => 'Salat berjamaah lima waktu',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-KW-18',
                'name'                  => 'Memakai seragam almamater saat pulang dan kembali ke lingkungan P2AL II',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptDepak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-19',
                'name'                  => 'Mengikuti seluruh kegiatan pengajian Al-Qur\'an sesuai dengan jadwal yang telah ditentukan',
                'default_sanction'      => 'Mengaji Al-Quran.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptDepak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-20',
                'name'                  => 'Mengikuti seluruh kegiatan pengajian kitab sesuai dengan jadwal yang telah ditentukan',
                'default_sanction'      => 'Membaca Sholawat Nariyah.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptDepak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-KW-21',
                'name'                  => 'Membeli surat izin apabila berhalangan mengikuti kegiatan pengajian Al-Qur\'an dan/atau pengajian kitab',
                'default_sanction'      => 'Membaca Sholawat Nariyah.',
                'is_active'             => true,
            ],

            // ── BAB II: LARANGAN-LARANGAN ─────────────────────────────────────
            // Kategori (Bab IV): Berat = ayat 1,17,18,19,21,27,31 | Ringan = ayat 8,12,33,35,39 | Sedang = selebihnya

            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-01',
                'name'                  => 'Menyalahgunakan kartu atau memalsukan tanda tangan',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-02',
                'name'                  => 'Memanjangkan rambut melebihi ketentuan , memotong menyerupai laki-laki , mengecat rambut atau kuku',
                'default_sanction'      => 'Rambut atau kuku dipotong; Membersihkan lingkungan P2AL II; Membaca sayyidul istighfar.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-03',
                'name'                  => 'Berkata kasar , nyaring , keji , kotor , berbahasa Madura kasar , tertawa nyaring , atau bersikap tidak sopan',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membaca sayyidul istighfar.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-04',
                'name'                  => 'Pinjam-meminjam pakaian',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membaca sayyidul istighfar.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-05',
                'name'                  => 'Membuat seragam selain yang dilegalkan',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-06',
                'name'                  => 'Menggunakan make up berlebihan , tindik berlebihan , dan sejenisnya',
                'default_sanction'      => 'Barang menjadi hak milik pesantren; Membersihkan lingkungan P2AL II; Membaca sayyidul istighfar.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-07',
                'name'                  => 'Memakai aksesori menyerupai laki-laki',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-LR-08',
                'name'                  => 'Mengganggu kegiatan yang diadakan pengurus P2AL II',
                'default_sanction'      => 'Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],

            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-10',
                'name'                  => 'Membaca novel dan komik di luar jam baca perpustakaan',
                'default_sanction'      => 'Barang menjadi hak milik pesantren; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-11',
                'name'                  => 'Mandi melebihi batas waktu',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membaca Burdah, Hirzih, dan lain-lain; Dikonfirmasikan kepada walinya.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-LR-12',
                'name'                  => 'Berada di luar daerah P2AL II melewati pukul 16.30 WIB tanpa alasan yang dibenarkan',
                'default_sanction'      => 'Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-13',
                'name'                  => 'Dikunjungi pada malam hari atau di luar hari kunjungan tanpa izin',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membayar infaq sesuai ketentuan; Membersihkan lingkungan; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-14',
                'name'                  => 'Pulang melebihi batas waktu tanpa prosedur',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Dikonfirmasikan kepada walinya.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-15',
                'name'                  => 'Memasuki area pesantren dalam rentang waktu pulang',
                'default_sanction'      => 'Membayar infaq sesuai ketentuan; Dikurangi jumlah hari libur pesantren.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-16',
                'name'                  => 'Bermalam , berkunjung , beristirahat atau berdiam lama di daerah lain',
                'default_sanction'      => 'Audensi menggunakan atribut pelanggaran.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-17',
                'name'                  => 'Berhubungan dengan selain mahram',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Meminta maaf kepada pengasuh atau pengurus; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-18',
                'name'                  => 'Keluar lingkungan P2AL II dengan selain mahram',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Meminta maaf kepada pengasuh atau pengurus; Membersihkan lingkungan; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain; Masuk Kamar Pembinaan Khusus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-19',
                'name'                  => 'Bergaul sesama jenis yang melampaui batas',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Meminta maaf kepada pengasuh atau pengurus; Audensi menggunakan atribut pelanggaran; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain; Masuk Kamar Pembinaan Khusus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-20',
                'name'                  => 'Bermain kartu atau permainan lainnya',
                'default_sanction'      => 'Dikonfirmasikan kepada walinya.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-21',
                'name'                  => 'Mengunggah foto atau video yang tidak pantas ke media sosial',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Barang menjadi hak milik pesantren; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-22',
                'name'                  => 'Menonton video',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-23',
                'name'                  => 'Naik atau duduk di tembok balkon dan gerbang taman',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membayar infaq sesuai ketentuan; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-24',
                'name'                  => 'Pulang pada hari-hari tahrim',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membayar infaq sesuai ketentuan; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-25',
                'name'                  => 'Memalsukan surat yang diterbitkan instansi mana pun',
                'default_sanction'      => 'Hafalan SKIA.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-26',
                'name'                  => 'Minum-minuman memabukkan dan merokok',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Dikonfirmasikan kepada walinya.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-27',
                'name'                  => 'Menyimpan , membawa , dan mengoperasikan alat elektronik',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Barang menjadi hak milik pesantren; Membersihkan lingkungan; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-28',
                'name'                  => 'Mengoperasikan internet dan rental komputer ilegal',
                'default_sanction'      => 'Membayar infaq sesuai ketentuan; Barang menjadi hak milik pesantren; Membersihkan lingkungan; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-29',
                'name'                  => 'Mengoperasikan Hand Phone pesantren tanpa izin',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptPeribadatan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-30',
                'name'                  => 'Membaca buku ketika kegiatan ubudiyah dan jam belajar berlangsung',
                'default_sanction'      => 'Barang menjadi hak milik pesantren; Membersihkan lingkungan; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptPeribadatan->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'PP-LR-31',
                'name'                  => 'Melalaikan salat',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Barang menjadi hak milik pesantren; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptPeribadatan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-32',
                'name'                  => 'Mengotori atau meninggalkan najis di Musala Ar-Rahmah',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Dikonfirmasikan kepada walinya; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-LR-33',
                'name'                  => 'Mandi atau mencuci di kamar mandi serta mencuci barang ke dalam kolam',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-34',
                'name'                  => 'Meng-ghasab milik orang lain',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-LR-35',
                'name'                  => 'Tidur pagi /sore di luar ketentuan atau tidak tidur sesuai waktu yang ditentukan',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-36',
                'name'                  => 'Melewati batas pintu utara atau berada di sepanjang jalan umum',
                'default_sanction'      => 'Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-37',
                'name'                  => 'Memegang kartu mahram sendiri',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-38',
                'name'                  => 'Mengunjungi kamar orang lain lebih dari 30 menit',
                'default_sanction'      => 'Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-LR-39',
                'name'                  => 'Menggunakan perhiasan emas selain sepasang anting',
                'default_sanction'      => 'Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-40',
                'name'                  => 'Merayakan ulang tahun , memberi hadiah , merayakan Valentine, dan sejenisnya',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-41',
                'name'                  => 'Mengikuti kegiatan ekstra kampus atau kegiatan di luar PP. Annuqayah tanpa izin pengasuh',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptAkhlak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-42',
                'name'                  => 'Minum dan/ atau makan berdiri',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptKeamanan->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-43',
                'name'                  => 'Melakukan transaksi jual beli ilegal di lingkungan PP. Annuqayah',
                'default_sanction'      => 'Meminta maaf kepada pengasuh atau pengurus; Membersihkan lingkungan; Membaca Burdah, Hirzih, dan lain-lain.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptDepak->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'PP-LR-44',
                'name'                  => 'Terlambat mengikuti kegiatan pengajian kitab',
                'default_sanction'      => 'Membaca Sholawat Nariyah.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'pesantren',
                'department_id'         => $deptDepak->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'PP-LR-45',
                'name'                  => 'Memalsukan surat izin kegiatan pengajian Al-Qur\'an dan/atau pengajian kitab',
                'default_sanction'      => 'Membaca Sholawat Nariyah.',
                'is_active'             => true,
            ],

            // =================================================================
            // TATA TERTIB MADRASAH DINIAH ANNUQAYAH LATEE II  (ruleset = madrasah)
            // =================================================================

            // ── BAB I: KEWAJIBAN-KEWAJIBAN ────────────────────────────────────
            // Kategori (Bab IV): Berat = poin 1 | Sedang = poin 2, 6 | Ringan = poin 3, 4, 5

            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'MDL-KW-01',
                'name'                  => 'Menjaga nama baik MADAL II serta mengikuti peraturan yang telah ditetapkan',
                'default_sanction'      => 'Meminta maaf kepada pengasuh.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'MDL-KW-02',
                'name'                  => 'Mengikuti seluruh kegiatan yang diprogramkan MADAL II',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-KW-03',
                'name'                  => 'Membawa materi pelajaran sesuai dengan jadwal yang ditentukan',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-KW-04',
                'name'                  => 'Berada di dalam kelas setelah bel tanda masuk dibunyikan',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-KW-05',
                'name'                  => 'Membawa kitab bacaan wajib sesuai dengan tingkatan masing-masing',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'MDL-KW-06',
                'name'                  => 'Melunasi seluruh keuangan MADAL II sesuai dengan waktu yang telah ditentukan',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],

            // ── BAB II: LARANGAN-LARANGAN ─────────────────────────────────────
            // Kategori (Bab IV): Berat = poin 1,2,7,10 | Sedang = poin 6,11 | Ringan = poin 3,4,5,8,9

            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'MDL-LR-01',
                'name'                  => 'Tidak Mengikuti KBM tanpa keterangan ( alpa )',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Menghafalkan bacaan wajib.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'MDL-LR-02',
                'name'                  => 'Membawa makanan dan minuman',
                'default_sanction'      => 'Barang menjadi hak milik MADAL II.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-LR-03',
                'name'                  => 'Mengotori lingkungan dan fasilitas sekolah',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'MDL-LR-04',
                'name'                  => 'Membawa , menghilangkan , dan merusak fasilitas sekolah',
                'default_sanction'      => 'Barang menjadi hak milik MADAL II.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-LR-05',
                'name'                  => 'Duduk di atas meja tulis dan kursi guru',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'MDL-LR-06',
                'name'                  => 'Meninggalkan kelas sebelum jam pelajaran berakhir',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'MDL-LR-07',
                'name'                  => 'Memalsukan surat izin Kegiatan Belajar Mengajar ( selanjutnya disebut KBM)',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Tidak dapat mengikuti ujian.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-LR-08',
                'name'                  => 'Membawa buku bacaan selain buku pelajaran ketika KBM berlangsung',
                'default_sanction'      => 'Barang menjadi hak milik MADAL II.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $ringan->id,
                'code'                  => 'MDL-LR-09',
                'name'                  => 'Berada di luar kelas atau di halaman ketika jam pelajaran kedua telah berakhir',
                'default_sanction'      => 'Teguran lisan oleh pengurus.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $berat->id,
                'code'                  => 'MDL-LR-10',
                'name'                  => 'Tidak mengikuti KBM mencapai 50% dengan keterangan atau 25% tanpa keterangan selama satu tahun pelajaran',
                'default_sanction'      => 'Meminta maaf kepada pengasuh; Tidak dapat mengikuti ujian; Tidak akan naik kelas.',
                'is_active'             => true,
            ],
            [
                'ruleset'               => 'madrasah',
                'department_id'         => $deptMadal->id,
                'violation_category_id' => $sedang->id,
                'code'                  => 'MDL-LR-11',
                'name'                  => 'Terlambat berangkat sekolah',
                'default_sanction'      => 'Membaca Sayyidul Istighfar.',
                'is_active'             => true,
            ],
        ];

        foreach ($types as $type) {
            ViolationType::firstOrCreate(
                ['code' => $type['code']],
                $type
            );
        }

        $countPesantren = collect($types)->where('ruleset', 'pesantren')->count();
        $countMadrasah  = collect($types)->where('ruleset', 'madrasah')->count();

        $this->command->info('✅ ViolationSeeder selesai:');
        $this->command->info("   · {$countPesantren} jenis pelanggaran Tata Tertib Pondok Pesantren P2AL II");
        $this->command->info("   · {$countMadrasah}  jenis pelanggaran Tata Tertib Madrasah Diniah (MADAL)");
        $this->command->info('   · Total: ' . count($types) . ' jenis pelanggaran dari 3 kategori.');
    }
}

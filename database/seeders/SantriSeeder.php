<?php

namespace Database\Seeders;

use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $santri = [
            [
                'nis' => 'S001',
                'nama' => 'Ahmad Fauzi',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2010-05-15',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'nama_ortu' => 'Budi Santoso',
                'no_hp_ortu' => '081234567890',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S002',
                'nama' => 'Siti Aminah',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '2011-03-22',
                'alamat' => 'Jl. Raya Bogor KM 30, Bogor',
                'nama_ortu' => 'Surya Wijaya',
                'no_hp_ortu' => '081298765432',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S003',
                'nama' => 'Muhammad Rizki',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Depok',
                'tanggal_lahir' => '2010-11-10',
                'alamat' => 'Jl. Raya Depok No. 45, Depok',
                'nama_ortu' => 'Dewi Lestari',
                'no_hp_ortu' => '081312345678',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S004',
                'nama' => 'Nurul Hikmah',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Tangerang',
                'tanggal_lahir' => '2011-07-18',
                'alamat' => 'Perumahan Taman Sari Blok A1, Tangerang',
                'nama_ortu' => 'Agus Setiawan',
                'no_hp_ortu' => '081512345678',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S005',
                'nama' => 'Abdul Rahman',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bekasi',
                'tanggal_lahir' => '2010-09-30',
                'alamat' => 'Jl. Raya Bekasi Timur No. 78, Bekasi',
                'nama_ortu' => 'Rina Wulandari',
                'no_hp_ortu' => '081612345678',
                'status' => 'non-aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S006',
                'nama' => 'Putri Ayu',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2011-01-25',
                'alamat' => 'Jl. Kebon Jeruk Raya No. 12, Jakarta Barat',
                'nama_ortu' => 'Andi Kurniawan',
                'no_hp_ortu' => '081712345678',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S007',
                'nama' => 'Daffa Maulana',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Tangerang Selatan',
                'tanggal_lahir' => '2010-12-05',
                'alamat' => 'BSD City, Sektor 1.1, Tangerang Selatan',
                'nama_ortu' => 'Rudi Hermawan',
                'no_hp_ortu' => '081812345678',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S008',
                'nama' => 'Aisyah Putri',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Depok',
                'tanggal_lahir' => '2011-04-17',
                'alamat' => 'Jl. Margonda Raya No. 100, Depok',
                'nama_ortu' => 'Bambang Sutrisno',
                'no_hp_ortu' => '081912345678',
                'status' => 'lulus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S009',
                'nama' => 'Fajar Ramadhan',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '2010-08-22',
                'alamat' => 'Perumahan Bogor Asri Blok C5, Bogor',
                'nama_ortu' => 'Eko Prasetyo',
                'no_hp_ortu' => '082112345678',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S010',
                'nama' => 'Dian Sastri',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2011-02-14',
                'alamat' => 'Jl. Jend. Sudirman Kav. 1, Jakarta Selatan',
                'nama_ortu' => 'Hendra Kurniawan',
                'no_hp_ortu' => '082212345678',
                'status' => 'drop-out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($santri as $data) {
            Santri::create($data);
        }
    }
}
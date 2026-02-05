<?php

namespace Database\Seeders;

use App\Models\Master\Student;
use App\Models\Master\Room;
use App\Models\Master\EducationLevel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Student::truncate();
        Schema::enableForeignKeyConstraints();

        // Get first room and education levels for seeding
        $room = Room::first();
        $diniyahLevel = EducationLevel::where('type', 'religious')->first();
        $formalLevel = EducationLevel::where('type', 'formal')->first();

        if (!$room) {
            $this->command->warn('No rooms found. Please run RoomSeeder first.');
            return;
        }

        $rayonA = \App\Models\Master\Rayon::where('name', 'Rayon A')->first();
        $rayonB = \App\Models\Master\Rayon::where('name', 'Rayon B')->first();
        $rayonC = \App\Models\Master\Rayon::where('name', 'Rayon C')->first();

        $santri = [
            [
                'name' => 'Ahmad Fauzi',
                'nis' => 'S001',
                'nik' => '3171051520100001',
                'gender' => 'male',
                'birth_place' => 'Jakarta',
                'birth_date' => '2010-05-15',
                'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'rayon_id' => $rayonA?->id,
                'room_id' => $room->id,
                'father_name' => 'Budi Santoso',
                'father_education' => 'S1',
                'father_occupation' => 'Pegawai Swasta',
                'mother_name' => 'Siti Nurhaliza',
                'mother_education' => 'SMA',
                'mother_occupation' => 'Ibu Rumah Tangga',
                'phone' => '081234567890',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'nis' => 'S002',
                'nik' => '3201062220110002',
                'gender' => 'female',
                'birth_place' => 'Bogor',
                'birth_date' => '2011-03-22',
                'address' => 'Jl. Raya Bogor KM 30, Bogor',
                'rayon_id' => $rayonB?->id,
                'room_id' => $room->id,
                'father_name' => 'Surya Wijaya',
                'father_occupation' => 'Wiraswasta',
                'mother_name' => 'Dewi Sartika',
                'mother_occupation' => 'Guru',
                'phone' => '081298765432',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Muhammad Rizki',
                'nis' => 'S003',
                'nik' => '3276101120100003',
                'gender' => 'male',
                'birth_place' => 'Depok',
                'birth_date' => '2010-11-10',
                'address' => 'Jl. Raya Depok No. 45, Depok',
                'rayon_id' => $rayonA?->id,
                'room_id' => $room->id,
                'father_name' => 'Hendra Kusuma',
                'father_occupation' => 'PNS',
                'mother_name' => 'Dewi Lestari',
                'mother_occupation' => 'Ibu Rumah Tangga',
                'phone' => '081312345678',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nurul Hikmah',
                'nis' => 'S004',
                'nik' => '3603051820110004',
                'gender' => 'female',
                'birth_place' => 'Tangerang',
                'birth_date' => '2011-07-18',
                'address' => 'Perumahan Taman Sari Blok A1, Tangerang',
                'rayon_id' => $rayonB?->id,
                'room_id' => $room->id,
                'father_name' => 'Agus Setiawan',
                'father_occupation' => 'Pedagang',
                'mother_name' => 'Rina Susanti',
                'mother_occupation' => 'Wirausaha',
                'phone' => '081512345678',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Abdul Rahman',
                'nis' => 'S005',
                'nik' => '3275093020100005',
                'gender' => 'male',
                'birth_place' => 'Bekasi',
                'birth_date' => '2010-09-30',
                'address' => 'Jl. Raya Bekasi Timur No. 78, Bekasi',
                'rayon_id' => $rayonA?->id,
                'room_id' => $room->id,
                'father_name' => 'Ahmad Dahlan',
                'father_occupation' => 'Petani',
                'mother_name' => 'Rina Wulandari',
                'mother_occupation' => 'Ibu Rumah Tangga',
                'phone' => '081612345678',
                'entry_date' => '2023-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Putri Ayu',
                'nis' => 'S006',
                'nik' => '3171012520110006',
                'gender' => 'female',
                'birth_place' => 'Jakarta',
                'birth_date' => '2011-01-25',
                'address' => 'Jl. Kebon Jeruk Raya No. 12, Jakarta Barat',
                'rayon_id' => $rayonC?->id,
                'room_id' => $room->id,
                'father_name' => 'Andi Kurniawan',
                'father_occupation' => 'Karyawan BUMN',
                'mother_name' => 'Sari Melati',
                'mother_occupation' => 'Dokter',
                'phone' => '081712345678',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Daffa Maulana',
                'nis' => 'S007',
                'nik' => '3674010520100007',
                'gender' => 'male',
                'birth_place' => 'Tangerang Selatan',
                'birth_date' => '2010-12-05',
                'address' => 'BSD City, Sektor 1.1, Tangerang Selatan',
                'rayon_id' => $rayonA?->id,
                'room_id' => $room->id,
                'father_name' => 'Rudi Hermawan',
                'father_occupation' => 'Pengusaha',
                'mother_name' => 'Ani Suryani',
                'mother_occupation' => 'Ibu Rumah Tangga',
                'phone' => '081812345678',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aisyah Putri',
                'nis' => 'S008',
                'nik' => '3276041720110008',
                'gender' => 'female',
                'birth_place' => 'Depok',
                'birth_date' => '2011-04-17',
                'address' => 'Jl. Margonda Raya No. 100, Depok',
                'rayon_id' => $rayonB?->id,
                'room_id' => $room->id,
                'father_name' => 'Bambang Sutrisno',
                'father_occupation' => 'Dosen',
                'mother_name' => 'Endang Rahayu',
                'mother_occupation' => 'Pegawai Swasta',
                'phone' => '081912345678',
                'entry_date' => '2023-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'graduated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fajar Ramadhan',
                'nis' => 'S009',
                'nik' => '3201082220100009',
                'gender' => 'male',
                'birth_place' => 'Bogor',
                'birth_date' => '2010-08-22',
                'address' => 'Perumahan Bogor Asri Blok C5, Bogor',
                'rayon_id' => $rayonA?->id,
                'room_id' => $room->id,
                'father_name' => 'Eko Prasetyo',
                'father_occupation' => 'TNI',
                'mother_name' => 'Lina Marlina',
                'mother_occupation' => 'Ibu Rumah Tangga',
                'phone' => '082112345678',
                'entry_date' => '2024-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dian Sastri',
                'nis' => 'S010',
                'nik' => '3171021420110010',
                'gender' => 'female',
                'birth_place' => 'Jakarta',
                'birth_date' => '2011-02-14',
                'address' => 'Jl. Jend. Sudirman Kav. 1, Jakarta Selatan',
                'rayon_id' => $rayonC?->id,
                'room_id' => $room->id,
                'father_name' => 'Hendra Kurniawan',
                'father_occupation' => 'Arsitek',
                'mother_name' => 'Maya Sari',
                'mother_occupation' => 'Akuntan',
                'phone' => '082212345678',
                'entry_date' => '2023-07-01',
                'religious_education_level_id' => $diniyahLevel?->id,
                'formal_education_level_id' => $formalLevel?->id,
                'status' => 'dropped_out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($santri as $data) {
            Student::create($data);
        }

        $this->command->info('Student data seeded successfully!');
    }
}
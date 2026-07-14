<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Master\Student;
use App\Models\Guardian;
use App\Models\Master\Rayon;
use App\Models\Master\Room;
use Faker\Factory as Faker;

class DummyStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $rayons = Rayon::pluck('id')->toArray();
        $rooms = Room::pluck('id')->toArray();
        $educationLevels = \App\Models\Master\EducationLevel::pluck('id')->toArray();
        $defaultPassword = Hash::make('password');

        $studentCount = 200;

        // Fetch 200 random villages to get valid region codes without querying in the loop
        $villages = \Laravolt\Indonesia\Models\Village::inRandomOrder()->limit($studentCount)->get();

        for ($i = 0; $i < $studentCount; $i++) {
            $rayonId = count($rayons) > 0 ? $rayons[array_rand($rayons)] : null;
            $roomId = count($rooms) > 0 ? $rooms[array_rand($rooms)] : null;
            $religiousEdId = count($educationLevels) > 0 ? $educationLevels[array_rand($educationLevels)] : null;
            $formalEdId = count($educationLevels) > 0 ? $educationLevels[array_rand($educationLevels)] : null;

            $village = $villages->get($i);
            $villageCode = $village ? $village->code : null;
            $districtCode = $village ? $village->district_code : null;
            // Laravolt uses 2 chars for province, 4 chars for city, 7 chars for district
            $cityCode = $districtCode ? substr($districtCode, 0, 4) : null;
            $provinceCode = $cityCode ? substr($cityCode, 0, 2) : null;

            // 1. Create Guardian
            $fatherName = $faker->firstName('male') . ' ' . $faker->lastName('male');
            $motherName = $faker->firstName('female') . ' ' . $faker->lastName('female');
            
            $guardianPhone = '08' . $faker->numerify('##########');
            
            $guardian = Guardian::create([
                'id' => (string) Str::uuid(),
                'name' => $fatherName,
                'username' => strtolower(explode(' ', $fatherName)[0]) . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'password' => $defaultPassword,
                'phone' => $guardianPhone,
                'email' => $faker->unique()->safeEmail(),
                'nik' => $faker->numerify('################'),
                'address' => $faker->address(),
                'job' => $faker->jobTitle(),
                'relationship' => 'father',
            ]);

            // 2. Create Student
            $gender = $faker->randomElement(['male', 'female']);
            $studentPhone = '08' . $faker->numerify('##########');
            $studentName = $faker->firstName($gender) . ' ' . $faker->lastName($gender);
            
            $student = Student::create([
                'id' => (string) Str::uuid(),
                'nis' => $faker->unique()->numerify('########'),
                'nik' => $faker->numerify('################'),
                'name' => $studentName,
                'gender' => $gender,
                'birth_place' => $faker->city(),
                'birth_date' => $faker->date('Y-m-d', '-12 years'),
                'religious_education_level_id' => $religiousEdId,
                'formal_education_level_id' => $formalEdId,
                'province_code' => $provinceCode,
                'city_code' => $cityCode,
                'district_code' => $districtCode,
                'village_code' => $villageCode,
                'address' => $faker->address(),
                'rayon_id' => $rayonId,
                'room_id' => $roomId,
                'father_name' => $fatherName,
                'father_education' => 'SMA',
                'father_occupation' => $faker->jobTitle(),
                'mother_name' => $motherName,
                'mother_education' => 'SMA',
                'mother_occupation' => 'Ibu Rumah Tangga',
                'entry_date' => today(),
                'phone' => $studentPhone,
                'status' => 'active',
            ]);

            // 3. Attach Guardian to Student via Pivot
            DB::table('student_guardian')->insert([
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
            ]);
        }
        
        $this->command->info("200 dummy students and guardians created successfully with all fields filled.");
    }
}

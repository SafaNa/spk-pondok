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

class SantriSeeder extends Seeder
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

        // Target distributions based on user request (for 200 students)
        $luarMaduraTarget = 10;
        $bangkalanTarget = 7;
        $sampangTarget = 4;
        $pamekasanTarget = 14;
        
        $sumenepCity = \Laravolt\Indonesia\Models\City::where('name', 'like', '%sumenep%')->first();
        $pamekasanCity = \Laravolt\Indonesia\Models\City::where('name', 'like', '%pamekasan%')->first();
        $sampangCity = \Laravolt\Indonesia\Models\City::where('name', 'like', '%sampang%')->first();
        $bangkalanCity = \Laravolt\Indonesia\Models\City::where('name', 'like', '%bangkalan%')->first();

        // Fetch villages for each category
        $luarMaduraVillages = \Laravolt\Indonesia\Models\Village::whereHas('district.city', function($q) use ($sumenepCity, $pamekasanCity, $sampangCity, $bangkalanCity) {
            $q->whereNotIn('code', [$sumenepCity->code, $pamekasanCity->code, $sampangCity->code, $bangkalanCity->code]);
        })->inRandomOrder()->limit(100)->get();

        $bangkalanVillages = \Laravolt\Indonesia\Models\Village::whereHas('district', function($q) use ($bangkalanCity) {
            $q->where('city_code', $bangkalanCity->code);
        })->inRandomOrder()->limit(50)->get();

        $sampangVillages = \Laravolt\Indonesia\Models\Village::whereHas('district', function($q) use ($sampangCity) {
            $q->where('city_code', $sampangCity->code);
        })->inRandomOrder()->limit(50)->get();

        $pamekasanVillages = \Laravolt\Indonesia\Models\Village::whereHas('district', function($q) use ($pamekasanCity) {
            $q->where('city_code', $pamekasanCity->code);
        })->inRandomOrder()->limit(50)->get();

        // Lenteng & Ganding
        $sumenepFavoredVillages = \Laravolt\Indonesia\Models\Village::whereHas('district', function($q) use ($sumenepCity) {
            $q->where('city_code', $sumenepCity->code)
              ->where(function($q2) {
                  $q2->where('name', 'like', '%lenteng%')->orWhere('name', 'like', '%ganding%')
                  ->orWhere('name', 'like', '%guluk%');
              });
        })->inRandomOrder()->limit(100)->get();

        // Other Sumenep
        $sumenepOtherVillages = \Laravolt\Indonesia\Models\Village::whereHas('district', function($q) use ($sumenepCity) {
            $q->where('city_code', $sumenepCity->code)
              ->where('name', 'not like', '%lenteng%')
              ->where('name', 'not like', '%ganding%')
              ->where('name', 'not like', '%guluk%');
        })->inRandomOrder()->limit(100)->get();


        for ($i = 0; $i < $studentCount; $i++) {
            $rayonId = count($rayons) > 0 ? $rayons[array_rand($rayons)] : null;
            $roomId = count($rooms) > 0 ? $rooms[array_rand($rooms)] : null;
            $religiousEdId = count($educationLevels) > 0 ? $educationLevels[array_rand($educationLevels)] : null;
            $formalEdId = count($educationLevels) > 0 ? $educationLevels[array_rand($educationLevels)] : null;

            if ($i < $luarMaduraTarget) {
                $village = $luarMaduraVillages->random();
            } elseif ($i < $luarMaduraTarget + $bangkalanTarget) {
                $village = $bangkalanVillages->random();
            } elseif ($i < $luarMaduraTarget + $bangkalanTarget + $sampangTarget) {
                $village = $sampangVillages->random();
            } elseif ($i < $luarMaduraTarget + $bangkalanTarget + $sampangTarget + $pamekasanTarget) {
                $village = $pamekasanVillages->random();
            } else {
                // For Sumenep, 70% chance to be in Lenteng/Ganding
                if (rand(1, 100) <= 70 && $sumenepFavoredVillages->isNotEmpty()) {
                    $village = $sumenepFavoredVillages->random();
                } else {
                    $village = $sumenepOtherVillages->random();
                }
            }

            $districtCode = $village->district_code;
            $cityCode = substr($districtCode, 0, 4);
            $provinceCode = substr($cityCode, 0, 2);
            $villageCode = $village->code;
            
            $cityName = \Laravolt\Indonesia\Models\City::where('code', $cityCode)->value('name');
            $birthPlace = ucwords(strtolower(str_replace(['KABUPATEN ', 'KOTA '], '', $cityName)));
            
            $villageName = ucwords(strtolower($village->name));
            $districtName = ucwords(strtolower($village->district->name));
            $provinceName = ucwords(strtolower(\Laravolt\Indonesia\Models\Province::where('code', $provinceCode)->value('name')));
            
            $address = "Dusun " . $faker->streetName() . " RT " . $faker->numerify('0#') . " RW " . $faker->numerify('0#') . ", Desa $villageName, Kec. $districtName, $cityName, $provinceName";

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
                'birth_place' => $birthPlace,
                'birth_date' => $faker->date('Y-m-d', '-12 years'),
                'religious_education_level_id' => $religiousEdId,
                'formal_education_level_id' => $formalEdId,
                'province_code' => $provinceCode,
                'city_code' => $cityCode,
                'district_code' => $districtCode,
                'village_code' => $villageCode,
                'address' => $address,
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

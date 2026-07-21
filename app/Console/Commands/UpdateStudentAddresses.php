<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Master\Student;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Village;

class UpdateStudentAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:update-addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update student addresses with specific distribution logic (Sumenep favored Lenteng/Ganding, specific counts for Madura regencies).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting address update...');

        $students = Student::all();
        $totalStudents = $students->count();
        
        if ($totalStudents === 0) {
            $this->error('No students found.');
            return;
        }

        // Target distributions based on user request
        $luarMaduraTarget = 10;
        $bangkalanTarget = 7;
        $sampangTarget = 4;
        $pamekasanTarget = 14;
        
        $sumenepTarget = $totalStudents - ($luarMaduraTarget + $bangkalanTarget + $sampangTarget + $pamekasanTarget);

        if ($sumenepTarget < 0) {
            $this->error("Total students ($totalStudents) is less than the required fixed non-Sumenep targets.");
            return;
        }

        $this->info("Targets -> Luar Madura: {$luarMaduraTarget}, Bangkalan: {$bangkalanTarget}, Sampang: {$sampangTarget}, Pamekasan: {$pamekasanTarget}, Sumenep: {$sumenepTarget}");

        // Find Cities
        $sumenepCity = City::where('name', 'like', '%sumenep%')->first();
        $pamekasanCity = City::where('name', 'like', '%pamekasan%')->first();
        $sampangCity = City::where('name', 'like', '%sampang%')->first();
        $bangkalanCity = City::where('name', 'like', '%bangkalan%')->first();

        if (!$sumenepCity || !$pamekasanCity || !$sampangCity || !$bangkalanCity) {
            $this->error('One or more Madura cities not found in database.');
            return;
        }

        $this->info('Fetching villages...');

        // Fetch random villages for each category
        $luarMaduraVillages = Village::whereHas('district.city', function($q) use ($sumenepCity, $pamekasanCity, $sampangCity, $bangkalanCity) {
            $q->whereNotIn('code', [$sumenepCity->code, $pamekasanCity->code, $sampangCity->code, $bangkalanCity->code]);
        })->inRandomOrder()->limit(100)->get();

        $bangkalanVillages = Village::whereHas('district', function($q) use ($bangkalanCity) {
            $q->where('city_code', $bangkalanCity->code);
        })->inRandomOrder()->limit(50)->get();

        $sampangVillages = Village::whereHas('district', function($q) use ($sampangCity) {
            $q->where('city_code', $sampangCity->code);
        })->inRandomOrder()->limit(50)->get();

        $pamekasanVillages = Village::whereHas('district', function($q) use ($pamekasanCity) {
            $q->where('city_code', $pamekasanCity->code);
        })->inRandomOrder()->limit(50)->get();

        // Lenteng & Ganding
        $sumenepFavoredVillages = Village::whereHas('district', function($q) use ($sumenepCity) {
            $q->where('city_code', $sumenepCity->code)
              ->where(function($q2) {
                  $q2->where('name', 'like', '%lenteng%')->orWhere('name', 'like', '%ganding%')
                  ->orWhere('name', 'like', '%guluk%');
              });
        })->inRandomOrder()->limit(100)->get();

        // Other Sumenep
        $sumenepOtherVillages = Village::whereHas('district', function($q) use ($sumenepCity) {
            $q->where('city_code', $sumenepCity->code)
              ->where('name', 'not like', '%lenteng%')
              ->where('name', 'not like', '%ganding%')
              ->where('name', 'not like', '%guluk%');
        })->inRandomOrder()->limit(100)->get();

        if ($luarMaduraVillages->isEmpty() || $sumenepOtherVillages->isEmpty()) {
            $this->error('Failed to load villages.');
            return;
        }

        $bar = $this->output->createProgressBar($totalStudents);
        $bar->start();
        
        $currentIndex = 0;

        foreach ($students as $student) {
            if ($currentIndex < $luarMaduraTarget) {
                $village = $luarMaduraVillages->random();
            } elseif ($currentIndex < $luarMaduraTarget + $bangkalanTarget) {
                $village = $bangkalanVillages->random();
            } elseif ($currentIndex < $luarMaduraTarget + $bangkalanTarget + $sampangTarget) {
                $village = $sampangVillages->random();
            } elseif ($currentIndex < $luarMaduraTarget + $bangkalanTarget + $sampangTarget + $pamekasanTarget) {
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

            $cityName = \Laravolt\Indonesia\Models\City::where('code', $cityCode)->value('name');
            $birthPlace = ucwords(strtolower(str_replace(['KABUPATEN ', 'KOTA '], '', $cityName)));
            
            $villageName = ucwords(strtolower($village->name));
            $districtName = ucwords(strtolower($village->district->name));
            
            $streetPrefixes = ['Jl. Raya', 'Jl. Merdeka', 'Jl. Pahlawan', 'Jl. Diponegoro', 'Jl. Sudirman', 'Jl. KH. Hasyim Asyari', 'Gg. Mawar', 'Gg. Melati'];
            $prefix = $streetPrefixes[array_rand($streetPrefixes)];
            $no = rand(1, 150);
            $rt = '0' . rand(1, 9);
            $rw = '0' . rand(1, 5);
            
            $fakeAddress = "{$prefix} {$villageName} No. {$no}, RT {$rt}/RW {$rw}";

            $student->update([
                'province_code' => $provinceCode,
                'city_code' => $cityCode,
                'district_code' => $districtCode,
                'village_code' => $village->code,
                'birth_place' => $birthPlace,
                'address' => $fakeAddress,
            ]);

            $currentIndex++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully updated student addresses!');
    }
}

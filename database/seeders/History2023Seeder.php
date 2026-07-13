<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Master\Student;
use App\Models\Master\Period;
use Carbon\Carbon;

class History2023Seeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $academicYear2023 = \App\Models\Master\AcademicYear::firstOrCreate(
            ['name' => '2023/2024'],
            ['id' => (string) Str::uuid(), 'start_date' => '2023-07-01', 'end_date' => '2024-06-30', 'status' => 'inactive']
        );
        $academicYearId2023 = $academicYear2023->id;

        // 1. Create or Find Periods for 2023/2024
        $periodGanjil = Period::firstOrCreate(
            ['academic_year_id' => $academicYearId2023, 'name' => 'Semester Ganjil 2023/2024'],
            ['id' => (string) Str::uuid(), 'is_active' => false]
        );
        $periodGenap = Period::firstOrCreate(
            ['academic_year_id' => $academicYearId2023, 'name' => 'Semester Genap 2023/2024'],
            ['id' => (string) Str::uuid(), 'is_active' => false]
        );

        // 2. Setup Base Data Arrays
        $students = Student::pluck('id')->toArray();
        if (empty($students)) {
            $this->command->error("No students found. Run dummy student seeder first.");
            return;
        }

        $userId = DB::table('users')->value('id') ?? 1;
        $violationTypes = DB::table('violation_types')->get();
        $leaveCategories = DB::table('leave_categories')->pluck('id')->toArray();
        $leaveReasons = DB::table('leave_reasons')->pluck('id')->toArray();
        $memorizationTypes = DB::table('memorization_types')->pluck('id')->toArray();

        // Data Generation Config
        $licensesCount = 500;
        $violationsCount = 400;
        $memorizationsCount = 200;

        $startDate = Carbon::create(2023, 7, 1);
        $endDate = Carbon::create(2024, 6, 30);

        $guardians = DB::table('guardians')->pluck('id')->toArray();

        // -- Generate Licenses --
        $this->command->info("Generating $licensesCount historical licenses...");
        $licenseData = [];
        for ($i = 0; $i < $licensesCount; $i++) {
            $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $endDateLicense = (clone $randomDate)->addDays(rand(1, 5));
            
            $source = rand(0, 1) === 1 ? 'admin' : 'guardian';
            $creatorId = $source === 'admin' ? $userId : (count($guardians) > 0 ? $guardians[array_rand($guardians)] : null);
            $creatorType = $source === 'admin' ? \App\Models\User::class : \App\Models\Guardian::class;
            
            $statusRand = rand(1, 100);
            if ($statusRand <= 10) {
                $status = 'pending';
            } elseif ($statusRand <= 25) {
                $status = 'rejected';
            } else {
                $status = 'approved';
            }
            
            $licenseDesc = ['Acara keluarga', 'Sakit demam', 'Urusan dokumen kependudukan', 'Menjenguk keluarga sakit', 'Walimatul ursy', 'Pulang kampung', 'Sakit gigi, perlu kontrol'];

            $licenseData[] = [
                'id' => (string) Str::uuid(),
                'student_id' => $students[array_rand($students)],
                'academic_year_id' => $academicYearId2023,
                'type' => 'individual',
                'start_date' => $randomDate->toDateString(),
                'end_date' => $endDateLicense->toDateString(),
                'actual_return_date' => $status === 'approved' ? (clone $endDateLicense)->addDays(rand(-1, 2))->toDateString() : null,
                'status' => $status,
                'leave_category_id' => count($leaveCategories) > 0 ? $leaveCategories[array_rand($leaveCategories)] : null,
                'leave_reason_id' => count($leaveReasons) > 0 ? $leaveReasons[array_rand($leaveReasons)] : null,
                'is_emergency' => rand(0, 1) === 1,
                'description' => $licenseDesc[array_rand($licenseDesc)],
                'submitted_at' => (clone $randomDate)->subDays(1),
                'approved_at' => $status === 'approved' ? $randomDate : null,
                'rejected_at' => $status === 'rejected' ? $randomDate : null,
                'source' => $source,
                'creator_id' => $creatorId,
                'creator_type' => $creatorType,
                'created_at' => clone $randomDate->subDays(1),
                'updated_at' => clone $randomDate,
            ];
        }
        foreach (array_chunk($licenseData, 100) as $chunk) {
            DB::table('student_licenses')->insert($chunk);
        }

        // -- Generate Violations --
        $this->command->info("Generating $violationsCount historical violations...");
        $violationData = [];
        for ($i = 0; $i < $violationsCount; $i++) {
            $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $periodId = $randomDate->month >= 7 && $randomDate->month <= 12 ? $periodGanjil->id : $periodGenap->id;

            $sanksiDesc = ['Membersihkan halaman asrama', 'Menghafal surat pendek', 'Disita barangnya selama 1 minggu', 'Diberikan peringatan keras', 'Merapikan masjid', 'Panggilan orang tua'];
            $notesDesc = ['Santri mengaku bersalah', 'Terlambat kembali ke asrama', 'Ditemukan barang bukti di lemari', 'Telah diselesaikan secara baik', 'Dilaporkan oleh pengurus keamanan'];

            $vType = count($violationTypes) > 0 ? $violationTypes[rand(0, count($violationTypes) - 1)] : null;

            $violationData[] = [
                'id' => (string) Str::uuid(),
                'student_id' => $students[array_rand($students)],
                'violation_type_id' => $vType ? $vType->id : null,
                'period_id' => $periodId,
                'date' => $randomDate->toDateString(),
                'sanction' => $vType ? $vType->default_sanction : 'Peringatan Lisan',
                'sanction_status' => rand(1, 10) > 2 ? 'completed' : 'pending',
                'notes' => $notesDesc[array_rand($notesDesc)],
                'created_by' => $userId,
                'verified_at' => clone $randomDate,
                'verified_by' => $userId,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }
        foreach (array_chunk($violationData, 100) as $chunk) {
            DB::table('violation_records')->insert($chunk);
        }

        // -- Generate Memorizations --
        $this->command->info("Generating $memorizationsCount historical memorizations...");
        $memorizationData = [];
        $educationLevels = ['MTS', 'MA', 'PT'];
        $memorizationItemsData = [];
        for ($i = 0; $i < $memorizationsCount; $i++) {
            $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $status = rand(1, 10) > 3 ? 'completed' : 'pending';
            $memId = (string) Str::uuid();

            $memorizationData[] = [
                'id' => $memId,
                'student_id' => $students[array_rand($students)],
                'academic_year_id' => $academicYearId2023,
                'education_level' => $educationLevels[array_rand($educationLevels)],
                'days' => rand(1, 6),
                'status' => $status,
                'completed_at' => $status === 'completed' ? clone $randomDate : null,
                'notes' => 'Hafalan rutin',
                'is_used' => rand(0, 1),
                'created_at' => clone $randomDate,
                'updated_at' => clone $randomDate,
            ];

            // Generate items
            if (count($memorizationTypes) > 0) {
                $numItems = rand(3, 7);
                $typesKeys = (array) array_rand($memorizationTypes, min($numItems, count($memorizationTypes)));
                foreach ($typesKeys as $k) {
                    $isChecked = $status === 'completed' ? true : (rand(0, 1) === 1);
                    $memorizationItemsData[] = [
                        'id' => (string) Str::uuid(),
                        'student_memorization_id' => $memId,
                        'memorization_type_id' => $memorizationTypes[$k],
                        'is_checked' => $isChecked,
                        'created_at' => clone $randomDate,
                        'updated_at' => clone $randomDate,
                    ];
                }
            }
        }
        foreach (array_chunk($memorizationData, 100) as $chunk) {
            DB::table('student_memorizations')->insert($chunk);
        }
        foreach (array_chunk($memorizationItemsData, 100) as $chunk) {
            DB::table('student_memorization_items')->insert($chunk);
        }

        $this->command->info("Successfully generated historical data for academic year 2023/2024.");
    }
}

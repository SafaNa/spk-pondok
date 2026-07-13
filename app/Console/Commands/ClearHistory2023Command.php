<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Period;

class ClearHistory2023Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:clear-2023';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all dummy historical data generated for the 2023/2024 academic year.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting cleanup of 2023/2024 historical dummy data...");
        $academicYearId2023 = '019f5564-f146-723b-be17-63ffb2cb2b35';

        // 1. Delete Licenses
        $deletedLicenses = DB::table('student_licenses')
            ->where('academic_year_id', $academicYearId2023)
            ->delete();
        $this->line("- Deleted {$deletedLicenses} student license records.");

        // 2. Delete Memorizations
        $deletedMems = DB::table('student_memorizations')
            ->where('academic_year_id', $academicYearId2023)
            ->delete();
        $this->line("- Deleted {$deletedMems} student memorization records.");

        // 3. Delete Violations based on Periods
        $periods = Period::where('academic_year_id', $academicYearId2023)->pluck('id')->toArray();
        if (!empty($periods)) {
            $deletedViolations = DB::table('violation_records')
                ->whereIn('period_id', $periods)
                ->delete();
            $this->line("- Deleted {$deletedViolations} violation records.");

            // 4. Delete the Periods themselves
            $deletedPeriods = DB::table('periods')
                ->whereIn('id', $periods)
                ->delete();
            $this->line("- Deleted {$deletedPeriods} period records for 2023/2024.");
        }

        $this->info("Cleanup completed successfully! Your database is now clean from 2023/2024 dummy data.");
    }
}

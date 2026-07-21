<?php

namespace App\Http\Controllers;

use App\Models\Master\Student;
use App\Models\Master\Period;
use App\Models\Finance\SppPayment;
use App\Models\Licensing\StudentLicense;
use App\Models\Violation\ViolationRecord;

use App\Models\Master\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return $this->licensingDashboard($request);
    }

    private function licensingDashboard(Request $request)
    {
        $allAcademicYears = AcademicYear::orderByDesc('name')->get();
        
        $selectedYearId = $request->input('academic_year_id');
        if ($selectedYearId) {
            $activeYear = AcademicYear::find($selectedYearId);
        } else {
            $activeYear = AcademicYear::where('status', 'active')->first();
        }
        
        $activeYearId = $activeYear ? $activeYear->id : null;
        $activePeriods = Period::where('academic_year_id', $activeYearId)->pluck('id')->toArray();

        $totalStudents   = Student::where('status', 'active')->count();
        $kepulangan      = StudentLicense::where('academic_year_id', $activeYearId)
                            ->where('status', 'approved')
                            ->whereDate('start_date', '<=', today())
                            ->whereDate('end_date', '>=', today())
                            ->whereNull('actual_return_date')
                            ->count();
        $izinDisetujui   = StudentLicense::where('academic_year_id', $activeYearId)->where('status', 'approved')->count();
        $izinPending     = StudentLicense::where('academic_year_id', $activeYearId)->where('status', 'pending')->count();
        $izinDitolak     = StudentLicense::where('academic_year_id', $activeYearId)->where('status', 'rejected')->count();

        // Tambahkan pengajuan perpanjangan ke perhitungan
        $extensions = \App\Models\Licensing\LicenseExtension::whereHas('studentLicense', function($q) use ($activeYearId) {
            $q->where('academic_year_id', $activeYearId);
        })->get();

        $extTotal    = $extensions->count();
        $extApproved = $extensions->where('status', 'approved')->count();
        $extPending  = $extensions->where('status', 'pending')->count();
        $extRejected = $extensions->where('status', 'rejected')->count();

        $kasusDarurat    = StudentLicense::where('academic_year_id', $activeYearId)
                            ->where('is_emergency', true)
                            ->where('status', 'pending')
                            ->count();

        $recentLicenses = StudentLicense::with(['student'])
            ->where('academic_year_id', $activeYearId)
            ->latest()
            ->limit(8)
            ->get();

        // Notifications: students with pending violations + pending licenses
        $violationNotifs = Student::with([
                'pendingViolations',
                'licenses' => fn($q) => $q->where('academic_year_id', $activeYearId)->where('status', 'pending'),
            ])
            ->whereHas('pendingViolations')
            ->whereHas('licenses', fn($q) => $q->where('academic_year_id', $activeYearId)->where('status', 'pending'))
            ->limit(5)
            ->get();

        // Quota warnings: students who used >= (max_leaves - 1) leaves this year
        $quotaWarnings = collect();
        if ($activeYear && $activeYear->max_leaves > 0) {
            $threshold = max(1, $activeYear->max_leaves - 1);
            $quotaWarnings = Student::where('status', 'active')
                ->whereHas('licenses', function ($q) use ($activeYearId) {
                    $q->where('academic_year_id', $activeYearId)
                      ->where('status', 'approved');
                }, '>=', $threshold)
                ->with(['licenses' => fn($q) => $q->where('academic_year_id', $activeYearId)->where('status', 'approved')])
                ->limit(5)
                ->get()
                ->map(fn($s) => (object)[
                    'name'       => $s->name,
                    'used_count' => $s->licenses->count(),
                    'max_leaves' => $activeYear->max_leaves,
                ]);
        }

        // --- DATA FOR CHARTS --- //

        // 2. Top Alasan Izin (Bar)
        $topReasons = StudentLicense::join('leave_reasons', 'student_licenses.leave_reason_id', '=', 'leave_reasons.id')
            ->where('student_licenses.academic_year_id', $activeYearId)
            ->select('leave_reasons.reason', DB::raw('count(student_licenses.id) as total'))
            ->groupBy('leave_reasons.id', 'leave_reasons.reason')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $driver = DB::connection()->getDriverName();
        $monthFormatSql = $driver === 'pgsql' ? "to_char(start_date, 'Mon YYYY')" : 'DATE_FORMAT(start_date, "%b %Y")';
        $yearSql = $driver === 'pgsql' ? 'EXTRACT(YEAR FROM start_date)' : 'YEAR(start_date)';
        $monthSql = $driver === 'pgsql' ? 'EXTRACT(MONTH FROM start_date)' : 'MONTH(start_date)';

        // 3. Tren Pengajuan Izin Bulanan (Area/Line)
        $licenseTrend = StudentLicense::selectRaw($monthFormatSql . ' as month_name, count(*) as total')
            ->where('academic_year_id', $activeYearId)
            ->groupBy('month_name', DB::raw($yearSql), DB::raw($monthSql))
            ->orderByRaw($yearSql . ', ' . $monthSql)
            ->get();

        // 4. Kategori Pelanggaran (Doughnut)
        $violationCategories = ViolationRecord::join('violation_types', 'violation_records.violation_type_id', '=', 'violation_types.id')
            ->join('violation_categories', 'violation_types.violation_category_id', '=', 'violation_categories.id')
            ->whereIn('violation_records.period_id', $activePeriods)
            ->select('violation_categories.name', DB::raw('count(violation_records.id) as total'))
            ->groupBy('violation_categories.id', 'violation_categories.name')
            ->get();

        $vMonthFormatSql = $driver === 'pgsql' ? "to_char(date, 'Mon YYYY')" : 'DATE_FORMAT(date, "%b %Y")';
        $vYearSql = $driver === 'pgsql' ? 'EXTRACT(YEAR FROM date)' : 'YEAR(date)';
        $vMonthSql = $driver === 'pgsql' ? 'EXTRACT(MONTH FROM date)' : 'MONTH(date)';

        // 5. Tren Pelanggaran Bulanan (Area)
        $violationTrend = ViolationRecord::selectRaw($vMonthFormatSql . ' as month_name, count(*) as total')
            ->whereIn('period_id', $activePeriods)
            ->groupBy('month_name', DB::raw($vYearSql), DB::raw($vMonthSql))
            ->orderByRaw($vYearSql . ', ' . $vMonthSql)
            ->get();

        // 6. Top 5 Rayon Pelanggaran (Horizontal Bar)
        $topRayons = ViolationRecord::join('students', 'violation_records.student_id', '=', 'students.id')
            ->join('rayons', 'students.rayon_id', '=', 'rayons.id')
            ->whereIn('violation_records.period_id', $activePeriods)
            ->select('rayons.name', DB::raw('count(violation_records.id) as total'))
            ->groupBy('rayons.id', 'rayons.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 8. Sebaran Santri per Rayon (Bar/Doughnut)
        $studentDemographics = Student::join('rayons', 'students.rayon_id', '=', 'rayons.id')
            ->where('students.status', 'active')
            ->select('rayons.name', DB::raw('count(students.id) as total'))
            ->groupBy('rayons.id', 'rayons.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // 9. Top 10 Santri Paling Banyak Izin
        $topStudentLicenses = StudentLicense::join('students', 'student_licenses.student_id', '=', 'students.id')
            ->where('student_licenses.academic_year_id', $activeYearId)
            ->where('student_licenses.status', 'approved')
            ->select('students.name', DB::raw('count(student_licenses.id) as total'))
            ->groupBy('students.id', 'students.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // 10. Top 10 Santri Paling Banyak Melanggar
        $topStudentViolations = ViolationRecord::join('students', 'violation_records.student_id', '=', 'students.id')
            ->whereIn('violation_records.period_id', $activePeriods)
            ->select('students.name', DB::raw('count(violation_records.id) as total'))
            ->groupBy('students.id', 'students.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $chartData = [
            'topReasons' => [
                'labels' => $topReasons->pluck('reason')->toArray(),
                'series' => $topReasons->pluck('total')->toArray(),
            ],
            'licenseTrend' => [
                'labels' => $licenseTrend->pluck('month_name')->toArray(),
                'series' => $licenseTrend->pluck('total')->toArray(),
            ],
            'violationCat' => [
                'labels' => $violationCategories->pluck('name')->toArray(),
                'series' => $violationCategories->pluck('total')->toArray(),
            ],
            'violationTrend' => [
                'labels' => $violationTrend->pluck('month_name')->toArray(),
                'series' => $violationTrend->pluck('total')->toArray(),
            ],
            'topRayons' => [
                'labels' => $topRayons->pluck('name')->toArray(),
                'series' => $topRayons->pluck('total')->toArray(),
            ],

            'demographics' => [
                'labels' => $studentDemographics->pluck('name')->toArray(),
                'series' => $studentDemographics->pluck('total')->toArray(),
            ],
            'topStudentLicenses' => [
                'labels' => $topStudentLicenses->pluck('name')->toArray(),
                'series' => $topStudentLicenses->pluck('total')->toArray(),
            ],
            'topStudentViolations' => [
                'labels' => $topStudentViolations->pluck('name')->toArray(),
                'series' => $topStudentViolations->pluck('total')->toArray(),
            ],
        ];

        return view('licensing.dashboard', compact(
            'totalStudents', 'kepulangan', 'izinDisetujui', 
            'izinPending', 'izinDitolak', 'kasusDarurat',
            'recentLicenses', 'quotaWarnings', 'violationNotifs',
            'chartData', 'activeYear', 'allAcademicYears', 
            'extTotal', 'extApproved', 'extPending', 'extRejected'
        ));
    }
}

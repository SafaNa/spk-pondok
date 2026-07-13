<?php

namespace App\Http\Controllers;

use App\Models\Master\Student;
use App\Models\Master\Period;
use App\Models\Finance\SppPayment;
use App\Models\Licensing\StudentLicense;
use App\Models\Violation\ViolationRecord;

class DashboardController extends Controller
{
    public function index()
    {
        return $this->licensingDashboard();
    }

    private function licensingDashboard()
    {
        $activeYear = \App\Models\Master\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;
        $activePeriods = \App\Models\Master\Period::where('academic_year_id', $activeYearId)->pluck('id')->toArray();

        $totalStudents   = Student::where('status', 'active')->count();
        $kepulangan      = StudentLicense::where('academic_year_id', $activeYearId)
                            ->where('status', 'approved')
                            ->whereDate('start_date', '<=', today())
                            ->whereDate('end_date', '>=', today())
                            ->count();
        $izinDisetujui   = StudentLicense::where('academic_year_id', $activeYearId)->where('status', 'approved')->count();
        $izinPending     = StudentLicense::where('academic_year_id', $activeYearId)->where('status', 'pending')->count();
        $izinDitolak     = StudentLicense::where('academic_year_id', $activeYearId)->where('status', 'rejected')->count();
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
            ->select('leave_reasons.reason', \Illuminate\Support\Facades\DB::raw('count(student_licenses.id) as total'))
            ->groupBy('leave_reasons.id', 'leave_reasons.reason')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Tren Pengajuan Izin Bulanan (Area/Line)
        $licenseTrend = StudentLicense::selectRaw('DATE_FORMAT(start_date, "%b %Y") as month_name, count(*) as total')
            ->where('academic_year_id', $activeYearId)
            ->groupBy('month_name', \Illuminate\Support\Facades\DB::raw('YEAR(start_date)'), \Illuminate\Support\Facades\DB::raw('MONTH(start_date)'))
            ->orderByRaw('YEAR(start_date), MONTH(start_date)')
            ->get();

        // 4. Kategori Pelanggaran (Doughnut)
        $violationCategories = \App\Models\Violation\ViolationRecord::join('violation_types', 'violation_records.violation_type_id', '=', 'violation_types.id')
            ->join('violation_categories', 'violation_types.violation_category_id', '=', 'violation_categories.id')
            ->whereIn('violation_records.period_id', $activePeriods)
            ->select('violation_categories.name', \Illuminate\Support\Facades\DB::raw('count(violation_records.id) as total'))
            ->groupBy('violation_categories.id', 'violation_categories.name')
            ->get();

        // 5. Tren Pelanggaran Bulanan (Area)
        $violationTrend = \App\Models\Violation\ViolationRecord::selectRaw('DATE_FORMAT(date, "%b %Y") as month_name, count(*) as total')
            ->whereIn('period_id', $activePeriods)
            ->groupBy('month_name', \Illuminate\Support\Facades\DB::raw('YEAR(date)'), \Illuminate\Support\Facades\DB::raw('MONTH(date)'))
            ->orderByRaw('YEAR(date), MONTH(date)')
            ->get();

        // 6. Top 5 Rayon Pelanggaran (Horizontal Bar)
        $topRayons = \App\Models\Violation\ViolationRecord::join('students', 'violation_records.student_id', '=', 'students.id')
            ->join('rayons', 'students.rayon_id', '=', 'rayons.id')
            ->whereIn('violation_records.period_id', $activePeriods)
            ->select('rayons.name', \Illuminate\Support\Facades\DB::raw('count(violation_records.id) as total'))
            ->groupBy('rayons.id', 'rayons.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 8. Sebaran Santri per Rayon (Bar/Doughnut)
        $studentDemographics = Student::join('rayons', 'students.rayon_id', '=', 'rayons.id')
            ->where('students.status', 'active')
            ->select('rayons.name', \Illuminate\Support\Facades\DB::raw('count(students.id) as total'))
            ->groupBy('rayons.id', 'rayons.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // 9. Top 10 Santri Paling Banyak Izin
        $topStudentLicenses = \App\Models\Licensing\StudentLicense::join('students', 'student_licenses.student_id', '=', 'students.id')
            ->where('student_licenses.academic_year_id', $activeYearId)
            ->where('student_licenses.status', 'approved')
            ->select('students.name', \Illuminate\Support\Facades\DB::raw('count(student_licenses.id) as total'))
            ->groupBy('students.id', 'students.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // 10. Top 10 Santri Paling Banyak Melanggar
        $topStudentViolations = \App\Models\Violation\ViolationRecord::join('students', 'violation_records.student_id', '=', 'students.id')
            ->whereIn('violation_records.period_id', $activePeriods)
            ->select('students.name', \Illuminate\Support\Facades\DB::raw('count(violation_records.id) as total'))
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
            'totalStudents',
            'kepulangan',
            'izinDisetujui',
            'izinPending',
            'izinDitolak',
            'kasusDarurat',
            'recentLicenses',
            'violationNotifs',
            'quotaWarnings',
            'chartData'
        ));
    }
}

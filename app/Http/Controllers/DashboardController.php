<?php

namespace App\Http\Controllers;

use App\Models\Master\Student;
use App\Models\Master\Period;
use App\Models\Assessment\Assessment;
use App\Models\Finance\SppPayment;
use App\Models\Licensing\StudentLicense;
use App\Models\Violation\ViolationRecord;

class DashboardController extends Controller
{
    public function index()
    {
        // Get active period
        $activePeriod = Period::where('is_active', true)->first();

        // KPI Stats
        $totalStudents = Student::count();

        // New metrics for the updated system
        $totalPayments = SppPayment::count();
        $approvedLicenses = StudentLicense::where('status', 'approved')->count();
        $totalViolations = ViolationRecord::count();
        $totalLicenses = StudentLicense::count();

        // Stats currently placeholder until Calculation Module is refactored
        $assessedStudents = 0;
        $recommendedCount = $approvedLicenses;
        $notRecommendedCount = StudentLicense::where('status', 'rejected')->count();

        // Pending
        $pendingCount = StudentLicense::where('status', 'pending')->count();

        // Completion rate - now represents license validation rate
        $completionRate = $totalLicenses > 0 ? round(($approvedLicenses / $totalLicenses) * 100) : 0;

        // Percentages for the pie chart
        $recommendedPercent = $totalLicenses > 0 ? round(($approvedLicenses / $totalLicenses) * 100) : 0;
        $notRecommendedPercent = $totalLicenses > 0 ? round(($notRecommendedCount / $totalLicenses) * 100) : 0;
        $pendingPercent = $totalLicenses > 0 ? round(($pendingCount / $totalLicenses) * 100) : 0;

        return view('dashboard', compact(
            'activePeriod',
            'totalStudents',
            'totalPayments',
            'approvedLicenses',
            'totalViolations',
            'totalLicenses',
            'assessedStudents',
            'pendingCount',
            'completionRate',
            'recommendedCount',
            'notRecommendedCount',
            'recommendedPercent',
            'notRecommendedPercent',
            'pendingPercent'
        ));
    }
}

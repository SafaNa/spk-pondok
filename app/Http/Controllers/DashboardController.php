<?php

namespace App\Http\Controllers;

use App\Models\Master\Student;
use App\Models\Master\Period;
use App\Models\Assessment\Assessment;

class DashboardController extends Controller
{
    public function index()
    {
        // Get active period
        $activePeriod = Period::where('is_active', true)->first();

        // KPI Stats
        $totalStudents = Student::count();
        $totalCriteria = 0;

        // Stats currently placeholder until Calculation Module is refactored
        $assessedStudents = 0;
        $recommendedCount = 0;
        $notRecommendedCount = 0;

        // Pending
        $pendingCount = $totalStudents - $assessedStudents;

        // Completion rate
        $completionRate = $totalStudents > 0 ? round(($assessedStudents / $totalStudents) * 100) : 0;

        // Percentages
        $recommendedPercent = 0;
        $notRecommendedPercent = 0;
        $pendingPercent = $totalStudents > 0 ? round(($pendingCount / $totalStudents) * 100) : 0;

        return view('dashboard', compact(
            'activePeriod',
            'totalStudents',
            'totalCriteria',
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

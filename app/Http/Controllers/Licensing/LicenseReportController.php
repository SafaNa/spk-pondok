<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Licensing\StudentLicense;
use App\Models\Licensing\LeaveCategory;
use Carbon\Carbon;

class LicenseReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $query = StudentLicense::with(['student.rayon', 'student.room', 'leaveCategory'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->where('status', 'approved');

        $licenses = $query->get();

        // Rekapitulasi Umum
        $totalLicenses = $licenses->count();
        $totalLate = $licenses->filter(fn($l) => $l->is_late)->count();
        $totalOnTime = $licenses->filter(fn($l) => $l->actual_return_date && !$l->is_late)->count();
        $totalNotReturned = $licenses->filter(fn($l) => !$l->actual_return_date && now()->startOfDay()->lte($l->end_date))->count();

        // Rekap per Kategori
        $categoryStats = LeaveCategory::withCount([
            'licenses as approved_count' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->where('status', 'approved');
            }
        ])->get();

        $allLicenses = $licenses->sortByDesc('start_date')->values();

        return view('licensing.reports.index', compact(
            'month',
            'totalLicenses',
            'totalLate',
            'totalOnTime',
            'totalNotReturned',
            'categoryStats',
            'allLicenses'
        ));
    }
}

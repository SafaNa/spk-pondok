<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSantri = Santri::count();
        $totalKriteria = Kriteria::count();
        $totalPenilaian = Penilaian::distinct('santri_id')->count('santri_id');

        // Data for Charts
        // 1. Santri Status Distribution
        $santriStatus = Santri::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Top 5 Santri by Score (Active Period)
        $periode = \App\Models\Periode::where('is_active', true)->first();
        $topSantri = collect([]); // Default empty collection

        if ($periode) {
            $topSantri = \App\Models\RiwayatHitung::with('santri')
                ->where('periode_id', $periode->id)
                ->orderBy('nilai_akhir', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalSantri',
            'totalKriteria',
            'totalPenilaian',
            'santriStatus',
            'topSantri',
            'periode'
        ));
    }

    public function indexV2()
    {
        // Get active periode
        $activePeriode = \App\Models\Periode::where('is_active', true)->first();

        // KPI Stats
        $totalSantri = Santri::count();
        $totalKriteria = Kriteria::count();

        // Get assessed santri count (those with riwayat_hitung in active periode)
        $assessedSantri = 0;
        $recommendedCount = 0;
        $notRecommendedCount = 0;

        if ($activePeriode) {
            $assessedSantri = \App\Models\RiwayatHitung::where('periode_id', $activePeriode->id)->count();
            // Recommended = nilai_akhir >= 0.6
            $recommendedCount = \App\Models\RiwayatHitung::where('periode_id', $activePeriode->id)
                ->where('nilai_akhir', '>=', 0.6)
                ->count();
            $notRecommendedCount = \App\Models\RiwayatHitung::where('periode_id', $activePeriode->id)
                ->where('nilai_akhir', '<', 0.6)
                ->count();
        }

        // Pending = santri yang belum dinilai
        $pendingCount = $totalSantri - $assessedSantri;

        // Completion rate
        $completionRate = $totalSantri > 0 ? round(($assessedSantri / $totalSantri) * 100) : 0;

        // Top 5 Santri (highest nilai_akhir)
        $topSantri = collect([]);
        if ($activePeriode) {
            $topSantri = \App\Models\RiwayatHitung::with('santri')
                ->where('periode_id', $activePeriode->id)
                ->orderByDesc('nilai_akhir')
                ->limit(5)
                ->get();
        }

        // Recent Assessments (latest riwayat_hitung)
        $recentAssessments = \App\Models\RiwayatHitung::with(['santri', 'periode'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Recommendation percentages for donut chart
        $recommendedPercent = $assessedSantri > 0 ? round(($recommendedCount / $assessedSantri) * 100) : 0;
        $notRecommendedPercent = $assessedSantri > 0 ? round(($notRecommendedCount / $assessedSantri) * 100) : 0;
        $pendingPercent = $totalSantri > 0 ? round(($pendingCount / $totalSantri) * 100) : 0;

        return view('dashboard-v2', compact(
            'activePeriode',
            'totalSantri',
            'totalKriteria',
            'assessedSantri',
            'pendingCount',
            'completionRate',
            'topSantri',
            'recentAssessments',
            'recommendedCount',
            'notRecommendedCount',
            'recommendedPercent',
            'notRecommendedPercent',
            'pendingPercent'
        ));
    }
}

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

        // 2. Top 5 Santri by Score
        $topSantri = Santri::whereNotNull('nilai_akhir')
            ->orderBy('nilai_akhir', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSantri',
            'totalKriteria',
            'totalPenilaian',
            'santriStatus',
            'topSantri'
        ));
    }
}

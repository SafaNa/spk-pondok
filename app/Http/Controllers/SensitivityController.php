<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Periode;
use App\Models\Santri;
use App\Models\RiwayatHitung;
use Illuminate\Http\Request;

class SensitivityController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::all();
        $periode = Periode::where('is_active', true)->first();

        return view('sensitivity.index', compact('kriteria', 'periode'));
    }

    public function analyze(Request $request)
    {
        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        // 1. Get Simulation Weights
        $simulatedWeights = $request->input('weights', []);

        // Validate total weight must receive comparison logic if strict, 
        // but for sensitivity usually we allow free adjustment to see impact.
        // Let's normalize them just in case they don't sum to 100 for the formula.
        $totalInputWeight = array_sum($simulatedWeights);
        if ($totalInputWeight == 0) {
            return redirect()->back()->with('error', 'Total bobot tidak boleh 0.');
        }

        // 2. original data (Current Ranking)
        $originalRanking = RiwayatHitung::with('santri')
            ->where('periode_id', $periode->id)
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        // 3. Recalculate with new weights
        // We need raw assessment data for this.
        // In hitungNilaiAkhir we used relations, let's replicate logic efficiently.

        $kriteriaData = Kriteria::with('subkriteria')->get();
        $results = [];

        foreach ($originalRanking as $riwayat) {
            $santriId = $riwayat->santri_id;
            $newScore = 0;

            foreach ($kriteriaData as $k) {
                // Get original value (we can query or if we trust the Assessment exists)
                // For efficiency, we should eager load assessments in originalRanking query ideally.
                // But let's re-query to be safe and allow existing calculation logic reuse logic.

                // Optimized: Fetch one by one is slow but safe for now. 
                // Better: Validation step ensures assessment exists.

                $penilaian = \App\Models\Penilaian::where('santri_id', $santriId)
                    ->where('periode_id', $periode->id)
                    ->where('kriteria_id', $k->id)
                    ->first();

                $nilai = $penilaian ? $penilaian->nilai : 0;

                // Normalization (Standard SAW)
                if ($k->jenis == 'benefit') {
                    $max = $k->subkriteria->max('nilai');
                    $utility = $max > 0 ? $nilai / $max : 0;
                } else {
                    $min = $k->subkriteria->min('nilai');
                    $utility = $nilai > 0 ? $min / $nilai : 0;
                }

                // New Weight Calculation
                // Input weights are raw integers usually (e.g. 10, 20), need normalization to 1? 
                // Or if user input percent, we divide by totalInputWeight.
                $userWeight = $simulatedWeights[$k->id] ?? 0;
                $normalizedWeight = $userWeight / $totalInputWeight;

                $newScore += $utility * $normalizedWeight;
            }

            $results[] = [
                'santri' => $riwayat->santri,
                'original_score' => $riwayat->nilai_akhir,
                'new_score' => $newScore,
                // We'll calculate rank changes after sorting
            ];
        }

        // 4. Sort by New Score
        usort($results, function ($a, $b) {
            return $b['new_score'] <=> $a['new_score'];
        });

        // 5. Add Rank Change Info
        // Map original ranks
        $originalRankMap = [];
        foreach ($originalRanking as $index => $item) {
            $originalRankMap[$item->santri_id] = $index + 1;
        }

        foreach ($results as $index => &$res) {
            $res['new_rank'] = $index + 1;
            $res['original_rank'] = $originalRankMap[$res['santri']->id] ?? '-';
            $res['rank_change'] = $res['original_rank'] - $res['new_rank']; // Positive means moved UP (e.g. 5 -> 1 = +4)
        }

        return view('sensitivity.index', [
            'kriteria' => $kriteriaData,
            'periode' => $periode,
            'results' => $results,
            'simulatedWeights' => $simulatedWeights
        ]);
    }
}

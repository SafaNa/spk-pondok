<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Models\RiwayatHitung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerhitunganController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('subkriteria')->get();
        $santri = Santri::where('status', 'aktif')->get();
        $activePeriode = Periode::where('is_active', true)->first();

        return view('perhitungan.index', compact('kriteria', 'santri', 'activePeriode'));
    }

    // ...

    public function hitung(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|string|exists:subkriteria,id'
        ]);

        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode penilaian yang aktif. Silakan hubungi admin untuk mengaktifkan periode.');
        }

        $santri = Santri::findOrFail($request->santri_id);

        // Simpan penilaian
        foreach ($request->nilai as $kriteriaId => $subkriteriaId) {
            $kriteria = Kriteria::findOrFail($kriteriaId);
            $subkriteria = $kriteria->subkriteria->find($subkriteriaId);

            if ($subkriteria) {
                Penilaian::updateOrCreate(
                    [
                        'santri_id' => $santri->id,
                        'kriteria_id' => $kriteria->id,
                        'periode_id' => $periode->id
                    ],
                    [
                        'subkriteria_id' => $subkriteria->id,
                        'nilai' => $subkriteria->nilai
                    ]
                );
            }
        }

        // Hitung nilai akhir for this period
        $nilaiAkhir = $this->hitungNilaiAkhir($santri->id, $periode->id);

        return redirect()->route('perhitungan.hasil', $santri->id)
            ->with('success', 'Perhitungan berhasil disimpan untuk periode ' . $periode->nama);
    }

    public function hasil(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        // Determine Periode: Use requested period OR default to Active Period
        if ($request->has('periode_id')) {
            $periode = Periode::find($request->periode_id);
        } else {
            $periode = Periode::where('is_active', true)->first();
        }

        if (!$periode) {
            return redirect()->route('perhitungan.index')->with('error', 'Periode tidak ditemukan atau tidak ada periode aktif.');
        }

        $kriteria = Kriteria::with([
            'subkriteria',
            'penilaian' => function ($q) use ($id, $periode) {
                $q->where('santri_id', $id)
                    ->where('periode_id', $periode->id);
            }
        ])->get();

        $totalBobot = $kriteria->sum('bobot');

        // Cek kelengkapan data for this period
        $jumlahKriteria = Kriteria::count();
        $jumlahPenilaian = Penilaian::where('santri_id', $id)
            ->where('periode_id', $periode->id)
            ->count();
        $isComplete = $jumlahKriteria > 0 && $jumlahKriteria == $jumlahPenilaian;

        $perhitungan = null;
        if ($isComplete) {
            $perhitungan = $this->hitungNilaiAkhir($id, $periode->id, true);
        }

        return view('perhitungan.hasil', compact('santri', 'kriteria', 'totalBobot', 'perhitungan', 'isComplete', 'periode'));
    }

    public function hitungNilaiAkhir($santriId, $periodeId, $detail = false)
    {
        $kriteria = Kriteria::with([
            'subkriteria',
            'penilaian' => function ($q) use ($santriId, $periodeId) {
                $q->where('santri_id', $santriId)
                    ->where('periode_id', $periodeId);
            }
        ])->get();

        $totalBobot = $kriteria->sum('bobot');
        $nilaiAkhir = 0;
        $detailPerhitungan = [];
        $alasanComponents = [];

        foreach ($kriteria as $k) {
            $penilaian = $k->penilaian->first();
            $nilai = $penilaian ? $penilaian->nilai : 0;
            $bobotTernormalisasi = $k->bobot / $totalBobot;

            if ($k->jenis == 'benefit') {
                $max = $k->subkriteria->max('nilai');
                $normalisasi = $max > 0 ? $nilai / $max : 0;
            } else {
                $min = $k->subkriteria->min('nilai');
                $normalisasi = $nilai > 0 ? $min / $nilai : 0;
            }

            $nilaiKriteria = $normalisasi * $bobotTernormalisasi;
            $nilaiAkhir += $nilaiKriteria;

            // Collect complete data for narrative
            $alasanComponents[] = [
                'nama' => $k->nama_kriteria,
                'type' => strtolower($k->jenis),
                'normalisasi' => $normalisasi,
                'weight' => $bobotTernormalisasi
            ];

            if ($detail) {
                $min = $k->subkriteria->min('nilai');
                $max = $k->subkriteria->max('nilai');
                $detailPerhitungan[] = [
                    'kriteria' => $k->nama_kriteria,
                    'jenis' => $k->jenis,
                    'bobot' => $k->bobot,
                    'bobot_ternormalisasi' => $bobotTernormalisasi,
                    'nilai' => $nilai,
                    'min' => $min,
                    'max' => $max,
                    'normalisasi' => $normalisasi,
                    'total' => $nilaiKriteria,
                ];
            }
        }

        // --- Narrative Generation Logic ---

        // Categorize Traits
        $goodTraits = [];
        $mediumTraits = [];
        $badTraits = [];

        foreach ($alasanComponents as $comp) {
            $val = $comp['normalisasi'];
            if ($val >= 0.75) {
                $goodTraits[] = $comp;
            } elseif ($val >= 0.45) {
                $mediumTraits[] = $comp;
            } else {
                $badTraits[] = $comp;
            }
        }

        // Sort by weight (importance)
        $sortByWeight = function ($a, $b) {
            return $b['weight'] <=> $a['weight'];
        };
        usort($goodTraits, $sortByWeight);
        usort($mediumTraits, $sortByWeight);
        usort($badTraits, $sortByWeight);

        $alasan = "";
        $scoreStr = number_format($nilaiAkhir, 2, ',', '.');

        // Helper to join names naturally
        $joinNames = function ($traits) {
            $names = array_column($traits, 'nama');
            if (empty($names))
                return "";
            if (count($names) == 1)
                return $names[0];
            $last = array_pop($names);
            return implode(", ", $names) . " dan " . $last;
        };

        if ($nilaiAkhir >= 0.7) {
            // RECOMMENDED
            // Pattern: "Santri direkomendasikan karena memiliki [Good Traits w/ adjectives], serta [Cost Good] sehingga menghasilkan nilai akhir yang tinggi (0,90)."

            $phrases = [];

            // Handle Benefit vs Cost in Good Traits
            $benefitGood = array_filter($goodTraits, fn($i) => $i['type'] != 'cost');
            $costGood = array_filter($goodTraits, fn($i) => $i['type'] == 'cost'); // Low cost value = Good

            if (!empty($benefitGood)) {
                $names = $joinNames($benefitGood);
                $phrases[] = "memiliki {$names} yang sangat baik";
            }

            if (!empty($costGood)) {
                $names = $joinNames($costGood);
                $phrases[] = "tingkat {$names} yang rendah";
            }

            // Fallback if empty (shouldn't happen for high score)
            if (empty($phrases))
                $phrases[] = "memiliki performa keseluruhan yang sangat baik";

            $traitStr = implode(", ", $phrases);
            $alasan = "Santri direkomendasikan karena {$traitStr} sehingga menghasilkan nilai akhir yang tinggi ({$scoreStr}).";

        } elseif ($nilaiAkhir >= 0.4) {
            // CONSIDER
            // Pattern: "Santri masih dipertimbangkan karena nilai [Medium Traits] berada pada tingkat sedang, serta [Bad Traits] sehingga menurunkan nilai akhir meskipun [Good Traits] tergolong baik."

            $phrases = [];

            // Focus on Medium
            if (!empty($mediumTraits)) {
                $names = $joinNames($mediumTraits);
                $phrases[] = "nilai {$names} berada pada tingkat sedang";
            }

            // Mention Bad (Negatives)
            $badBenefit = array_filter($badTraits, fn($i) => $i['type'] != 'cost');
            $badCost = array_filter($badTraits, fn($i) => $i['type'] == 'cost');

            if (!empty($badCost)) {
                $names = $joinNames($badCost);
                $phrases[] = "tingkat {$names} yang masih cukup tinggi";
            }
            if (!empty($badBenefit)) {
                $names = $joinNames($badBenefit);
                $phrases[] = "nilai {$names} yang kurang memadai";
            }

            // Mention Good (Positives) for balance
            $goodStr = "";
            if (!empty($goodTraits)) {
                $names = $joinNames($goodTraits);
                $goodStr = " meskipun {$names} tergolong baik";
            }

            $traitStr = implode(", serta ", $phrases);
            $alasan = "Santri masih dipertimbangkan karena {$traitStr}{$goodStr}.";

        } else {
            // NOT RECOMMENDED
            // Pattern: "Santri tidak direkomendasikan karena memiliki nilai [Bad Traits Benefit] yang sangat rendah, [Bad Traits Cost] yang tinggi..."

            $phrases = [];

            $badBenefit = array_filter($badTraits, fn($i) => $i['type'] != 'cost');
            $badCost = array_filter($badTraits, fn($i) => $i['type'] == 'cost');

            if (!empty($badBenefit)) {
                $names = $joinNames($badBenefit);
                $phrases[] = "memiliki nilai {$names} yang sangat rendah";
            }

            if (!empty($badCost)) {
                $names = $joinNames($badCost);
                $phrases[] = "tingkat {$names} yang tinggi";
            }

            // If strictly medium but low score?
            if (empty($phrases) && !empty($mediumTraits)) {
                $names = $joinNames($mediumTraits);
                $phrases[] = "nilai {$names} yang belum optimal";
            }

            $traitStr = implode(", serta ", $phrases);
            $alasan = "Santri tidak direkomendasikan karena {$traitStr} sehingga menghasilkan nilai akhir yang rendah ({$scoreStr}).";
        }

        // Save and Update
        Santri::where('id', $santriId)->update(['nilai_akhir' => $nilaiAkhir]);
        RiwayatHitung::updateOrCreate(
            ['santri_id' => $santriId, 'periode_id' => $periodeId],
            ['nilai_akhir' => $nilaiAkhir, 'alasan' => $alasan]
        );

        return $detail ? [
            'nilai_akhir' => $nilaiAkhir,
            'detail' => $detailPerhitungan,
            'alasan' => $alasan
        ] : $nilaiAkhir;
    }

    public function destroy($santriId)
    {
        $activePeriode = Periode::where('is_active', true)->first();

        if (!$activePeriode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        try {
            DB::beginTransaction();

            // 1. Hapus Riwayat Hitung
            RiwayatHitung::where('santri_id', $santriId)
                ->where('periode_id', $activePeriode->id)
                ->delete();

            // 2. Hapus Penilaian
            Penilaian::where('santri_id', $santriId)
                ->where('periode_id', $activePeriode->id)
                ->delete();

            // 3. Reset Nilai Santri (Optional, atau set ke 0)
            Santri::where('id', $santriId)->update(['nilai_akhir' => 0]);

            DB::commit();

            return redirect()->route('perhitungan.rekomendasi')->with('success', 'Data hasil perhitungan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function rekomendasi()
    {
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            $santri = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20); // Empty paginator
            return view('perhitungan.rekomendasi', compact('santri', 'periode'));
        }

        $santri = RiwayatHitung::with('santri')
            ->where('periode_id', $periode->id)
            ->orderBy('nilai_akhir', 'desc')
            ->paginate(20);

        return view('perhitungan.rekomendasi', compact('santri', 'periode'));
    }

    public function rekomendasiV2()
    {
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            $riwayat = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $stats = ['total' => 0, 'recommended' => 0, 'consider' => 0, 'not_recommended' => 0];
            return view('rekomendasi-v2', compact('riwayat', 'periode', 'stats'));
        }

        $riwayat = RiwayatHitung::with('santri')
            ->where('periode_id', $periode->id)
            ->orderBy('nilai_akhir', 'desc')
            ->paginate(10);

        // Stats
        $allRiwayat = RiwayatHitung::where('periode_id', $periode->id)->get();
        $stats = [
            'total' => $allRiwayat->count(),
            'recommended' => $allRiwayat->where('nilai_akhir', '>=', 0.7)->count(),
            'consider' => $allRiwayat->whereBetween('nilai_akhir', [0.4, 0.7])->count(),
            'not_recommended' => $allRiwayat->where('nilai_akhir', '<', 0.4)->count(),
        ];

        return view('rekomendasi-v2', compact('riwayat', 'periode', 'stats'));
    }

    public function hasilV2($id)
    {
        $santri = Santri::findOrFail($id);
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            return redirect()->route('rekomendasi-v2')->with('error', 'Tidak ada periode aktif.');
        }

        $riwayat = RiwayatHitung::where('santri_id', $id)
            ->where('periode_id', $periode->id)
            ->first();

        if (!$riwayat) {
            return redirect()->route('rekomendasi-v2')->with('error', 'Data perhitungan tidak ditemukan.');
        }

        $perhitungan = $this->hitungNilaiAkhir($id, $periode->id, true);

        return view('rekomendasi-detail-v2', compact('santri', 'periode', 'riwayat', 'perhitungan'));
    }

    public function cetak()
    {
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $santri = RiwayatHitung::with('santri')
            ->where('periode_id', $periode->id)
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        return view('perhitungan.rekomendasi_print', compact('santri', 'periode'));
    }

    public function history(Request $request)
    {
        $query = RiwayatHitung::with(['santri', 'periode']);

        // Filter by Period
        // 1. Base Query for Periods
        $periodeQuery = Periode::orderBy('created_at', 'desc');
        $search = $request->search; // Initialize scope variable

        // UX: Default to Active Period if no filter provided
        if (!$request->has('periode_filter') && !$request->has('search')) {
            $activePeriode = Periode::where('is_active', true)->first();
            if ($activePeriode) {
                $request->merge(['periode_filter' => $activePeriode->id]);
            }
        }

        // Filter by specific period
        if ($request->filled('periode_filter')) {
            $periodeQuery->where('id', $request->periode_filter);
        }

        // Filter by search (only show periods that have matching records)
        if ($request->filled('search')) {
            $periodeQuery->whereHas('riwayatHitung', function ($q) use ($search) {
                $q->whereHas('santri', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                });
            });
        }


        $periodesPaginated = $periodeQuery->paginate(10); // Paginate periods

        // Load riwayatHitung for each period
        $periodesPaginated->getCollection()->each(function ($periode) use ($search) {
            $riwayatQuery = $periode->riwayatHitung()->with('santri');
            if ($search) {
                $riwayatQuery->whereHas('santri', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            $periode->setRelation('riwayatHitung', $riwayatQuery->orderBy('nilai_akhir', 'desc')->get());
        });

        $allPeriodes = Periode::orderBy('created_at', 'desc')->get(); // For the filter dropdown

        return view('perhitungan.history', compact('periodesPaginated', 'allPeriodes'));
    }

    public function historyV2(Request $request)
    {
        $periodeQuery = Periode::orderBy('created_at', 'desc');
        $search = $request->search;

        if (!$request->has('periode_filter') && !$request->has('search')) {
            $activePeriode = Periode::where('is_active', true)->first();
            if ($activePeriode) {
                $request->merge(['periode_filter' => $activePeriode->id]);
            }
        }

        if ($request->filled('periode_filter')) {
            $periodeQuery->where('id', $request->periode_filter);
        }

        if ($request->filled('search')) {
            $periodeQuery->whereHas('riwayatHitung', function ($q) use ($search) {
                $q->whereHas('santri', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                });
            });
        }

        $periodesPaginated = $periodeQuery->paginate(10);

        $periodesPaginated->getCollection()->each(function ($periode) use ($search) {
            $riwayatQuery = $periode->riwayatHitung()->with('santri');
            if ($search) {
                $riwayatQuery->whereHas('santri', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            $periode->setRelation('riwayatHitung', $riwayatQuery->orderBy('nilai_akhir', 'desc')->get());
        });

        $allPeriodes = Periode::orderBy('created_at', 'desc')->get();

        return view('riwayat-v2', compact('periodesPaginated', 'allPeriodes'));
    }

    public function recalculateBatch()
    {
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $riwayat = RiwayatHitung::where('periode_id', $periode->id)->get();

        $count = 0;
        foreach ($riwayat as $item) {
            $this->hitungNilaiAkhir($item->santri_id, $periode->id);
            $count++;
        }

        return redirect()->route('perhitungan.rekomendasi')
            ->with('success', "Berhasil menghitung ulang {$count} data santri.");
    }

    public function sensitivitasV2()
    {
        $kriteria = Kriteria::orderBy('kode_kriteria')->get();
        $periode = Periode::where('is_active', true)->first();

        $riwayat = collect([]);
        if ($periode) {
            $riwayat = RiwayatHitung::with('santri')
                ->where('periode_id', $periode->id)
                ->orderBy('nilai_akhir', 'desc')
                ->limit(10)
                ->get();
        }

        return view('sensitivitas-v2', compact('kriteria', 'periode', 'riwayat'));
    }
}
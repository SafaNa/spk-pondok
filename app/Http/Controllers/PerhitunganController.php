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

    private function hitungNilaiAkhir($santriId, $periodeId, $detail = false)
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

            $nilaiAkhir += $normalisasi * $bobotTernormalisasi;

            if ($detail) {
                // For detailed view, we still fetch min/max to show in the UI if needed,
                // but for SAW we primarily need Max for Benefit and Min for Cost.
                // We'll keep sending both to be safe for the view.
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
                    'normalisasi' => $normalisasi, // Renamed from utility
                    'total' => $normalisasi * $bobotTernormalisasi,
                ];
            }
        }

        // Update nilai akhir santri (Latest)
        Santri::where('id', $santriId)->update(['nilai_akhir' => $nilaiAkhir]);

        // Save to History
        RiwayatHitung::updateOrCreate(
            ['santri_id' => $santriId, 'periode_id' => $periodeId],
            ['nilai_akhir' => $nilaiAkhir]
        );

        return $detail ? [
            'nilai_akhir' => $nilaiAkhir,
            'detail' => $detailPerhitungan
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
}
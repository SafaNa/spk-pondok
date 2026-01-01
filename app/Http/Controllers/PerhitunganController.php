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

        return view('perhitungan.index', compact('kriteria', 'santri'));
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

    public function hasil($id)
    {
        $santri = Santri::findOrFail($id);
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            return redirect()->route('perhitungan.index')->with('error', 'Tidak ada periode aktif.');
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

            $min = $k->subkriteria->min('nilai');
            $max = $k->subkriteria->max('nilai');
            $range = $max - $min;

            if ($range == 0) {
                $utility = 1;
            } else {
                if ($k->jenis == 'benefit') {
                    $utility = ($nilai - $min) / $range;
                } else {
                    $utility = ($max - $nilai) / $range;
                }
            }

            $nilaiAkhir += $utility * $bobotTernormalisasi;

            if ($detail) {
                $detailPerhitungan[] = [
                    'kriteria' => $k->nama_kriteria,
                    'jenis' => $k->jenis,
                    'bobot' => $k->bobot,
                    'bobot_ternormalisasi' => $bobotTernormalisasi,
                    'nilai' => $nilai,
                    'min' => $min,
                    'max' => $max,
                    'utility' => $utility,
                    'total' => $utility * $bobotTernormalisasi,
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

    public function rekomendasi()
    {
        $santri = Santri::where('status', 'aktif')
            ->whereNotNull('nilai_akhir')
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        return view('perhitungan.rekomendasi', compact('santri'));
    }

    public function cetak()
    {
        $santri = Santri::where('status', 'aktif')
            ->whereNotNull('nilai_akhir')
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        return view('perhitungan.rekomendasi_print', compact('santri'));
    }

    public function history(Request $request)
    {
        $query = RiwayatHitung::with(['santri', 'periode']);

        // Filter by Period
        if ($request->has('periode_filter') && $request->periode_filter != '') {
            $query->where('periode_id', $request->periode_filter);
        }

        // Search by Santri Name or NIS
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('santri', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->latest()
            ->get()
            ->groupBy('periode_id');

        $periodes = Periode::orderBy('created_at', 'desc')->get();

        return view('perhitungan.history', compact('riwayat', 'periodes'));
    }
}
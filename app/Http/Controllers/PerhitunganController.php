<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kriteria;
use App\Models\Penilaian;
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

    public function hitung(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|string|exists:subkriteria,id'
        ]);

        $santri = Santri::findOrFail($request->santri_id);
        $kriteria = Kriteria::with('subkriteria')->get();
        $totalBobot = $kriteria->sum('bobot');

        // Simpan penilaian
        foreach ($request->nilai as $kriteriaId => $subkriteriaId) {
            $kriteria = Kriteria::findOrFail($kriteriaId);
            $subkriteria = $kriteria->subkriteria->find($subkriteriaId);

            if ($subkriteria) {
                Penilaian::updateOrCreate(
                    [
                        'santri_id' => $santri->id,
                        'kriteria_id' => $kriteria->id
                    ],
                    [
                        'subkriteria_id' => $subkriteria->id,
                        'nilai' => $subkriteria->nilai
                    ]
                );
            }
        }

        // Hitung nilai akhir
        $nilaiAkhir = $this->hitungNilaiAkhir($santri->id);

        return redirect()->route('perhitungan.hasil', $santri->id)
            ->with('success', 'Perhitungan berhasil disimpan');
    }

    public function hasil($id)
    {
        $santri = Santri::findOrFail($id);
        $kriteria = Kriteria::with([
            'subkriteria',
            'penilaian' => function ($q) use ($id) {
                $q->where('santri_id', $id);
            }
        ])->get();

        $totalBobot = $kriteria->sum('bobot');

        // Cek kelengkapan data
        $jumlahKriteria = Kriteria::count();
        $jumlahPenilaian = Penilaian::where('santri_id', $id)->count();
        $isComplete = $jumlahKriteria > 0 && $jumlahKriteria == $jumlahPenilaian;

        $perhitungan = null;
        if ($isComplete) {
            $perhitungan = $this->hitungNilaiAkhir($id, true);
        }

        return view('perhitungan.hasil', compact('santri', 'kriteria', 'totalBobot', 'perhitungan', 'isComplete'));
    }

    private function hitungNilaiAkhir($santriId, $detail = false)
    {
        $kriteria = Kriteria::with([
            'subkriteria',
            'penilaian' => function ($q) use ($santriId) {
                $q->where('santri_id', $santriId);
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

            // Guard against division by zero if max == min
            if ($range == 0) {
                // If max == min, all values are same, so utility is 1 (or 0, generally 1 if matches)
                // Let's assume 1 if logic holds, meaning it doesn't differentiate.
                $utility = 1;
            } else {
                if ($k->jenis == 'benefit') {
                    // Benefit: (Nilai - Min) / (Max - Min)
                    $utility = ($nilai - $min) / $range;
                } else {
                    // Cost: (Max - Nilai) / (Max - Min)
                    $utility = ($max - $nilai) / $range;
                }
            }

            $nilaiAkhir += $utility * $bobotTernormalisasi;

            if ($detail) {
                $detailPerhitungan[] = [
                    'kriteria' => $k->nama_kriteria,
                    'bobot' => $k->bobot,
                    'bobot_ternormalisasi' => $bobotTernormalisasi,
                    'nilai' => $nilai,
                    'utility' => $utility,
                    'total' => $utility * $bobotTernormalisasi,
                    'jenis' => $k->jenis,
                    'min' => $min,
                    'max' => $max,
                    'range' => $range
                ];
            }
        }

        // Update nilai akhir santri
        Santri::where('id', $santriId)->update(['nilai_akhir' => $nilaiAkhir]);

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
}
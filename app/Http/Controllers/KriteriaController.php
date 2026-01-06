<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('subkriteria')->get();
        $totalBobot = $kriteria->sum('bobot');
        return view('v2.kriteria.index', compact('kriteria', 'totalBobot'));
    }

    public function create()
    {
        $totalBobot = Kriteria::sum('bobot');
        $remainingBobot = 100 - $totalBobot;

        if ($remainingBobot <= 0) {
            return redirect()->route('kriteria.index')
                ->with('error', 'Total bobot sudah mencapai 100%. Harap kurangi bobot kriteria lain sebelum menambah yang baru.');
        }

        return view('v2.kriteria.form', compact('totalBobot', 'remainingBobot'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kriteria' => 'required|unique:kriteria,kode_kriteria',
            'nama_kriteria' => 'required',
            'bobot' => 'required|numeric|min:0',
            'jenis' => 'required|in:benefit,cost',
            'keterangan' => 'nullable|string',
        ]);

        $currentTotal = Kriteria::sum('bobot');
        if ($currentTotal + $request->bobot > 100) {
            return back()->withInput()->withErrors(['bobot' => 'Total bobot melebihi 100. Sisa bobot yang tersedia: ' . (100 - $currentTotal)]);
        }

        Kriteria::create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function edit(Kriteria $kriterium)
    {
        $totalBobot = Kriteria::where('id', '!=', $kriterium->id)->sum('bobot');
        $remainingBobot = 100 - $totalBobot;
        return view('v2.kriteria.form', compact('kriterium', 'totalBobot', 'remainingBobot'));
    }

    public function update(Request $request, Kriteria $kriterium)
    {
        $validated = $request->validate([
            'kode_kriteria' => 'required|unique:kriteria,kode_kriteria,' . $kriterium->id,
            'nama_kriteria' => 'required',
            'bobot' => 'required|numeric|min:0',
            'jenis' => 'required|in:benefit,cost',
            'keterangan' => 'nullable|string',
        ]);

        $currentTotal = Kriteria::where('id', '!=', $kriterium->id)->sum('bobot');
        if ($currentTotal + $request->bobot > 100) {
            return back()->withInput()->withErrors(['bobot' => 'Total bobot melebihi 100. Sisa bobot yang tersedia: ' . (100 - $currentTotal)]);
        }

        $kriterium->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui');
    }

    public function destroy(Kriteria $kriterium)
    {
        $kriterium->delete();
        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus');
    }
}
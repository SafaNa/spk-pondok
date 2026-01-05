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
        return view('kriteria.index', compact('kriteria', 'totalBobot'));
    }

    public function indexV2()
    {
        $kriteria = Kriteria::with('subkriteria')->get();
        $totalBobot = $kriteria->sum('bobot');
        return view('kriteria-v2', compact('kriteria', 'totalBobot'));
    }

    public function createV2()
    {
        $totalBobot = Kriteria::sum('bobot');
        $remainingBobot = 100 - $totalBobot;
        return view('kriteria-form-v2', compact('totalBobot', 'remainingBobot'));
    }

    public function create()
    {
        $currentTotal = Kriteria::sum('bobot');
        if ($currentTotal >= 100) {
            return redirect()->route('kriteria.index')
                ->with('error', 'Total bobot sudah mencapai 100%. Harap kurangi bobot kriteria lain sebelum menambah yang baru.');
        }
        return view('kriteria.create');
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

        $kriteria = Kriteria::create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function editV2($id)
    {
        $kriterium = Kriteria::findOrFail($id);
        $totalBobot = Kriteria::where('id', '!=', $id)->sum('bobot');
        $remainingBobot = 100 - $totalBobot;
        return view('kriteria-form-v2', compact('kriterium', 'totalBobot', 'remainingBobot'));
    }

    public function edit(Kriteria $kriterium)
    {
        return view('kriteria.edit', compact('kriterium'));
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
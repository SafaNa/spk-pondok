<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $activePeriode = Periode::where('is_active', true)->first();
        $periodes = Periode::where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('v2.periode.index', compact('activePeriode', 'periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        Periode::create($request->all());

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan');
    }

    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $periode->update($request->all());

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_active) {
            return back()->with('error', 'Tidak dapat menghapus periode aktif.');
        }

        $periode->delete();
        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus');
    }

    public function activate(Periode $periode)
    {
        // Deactivate all
        Periode::where('is_active', true)->update(['is_active' => false]);

        // Activate selected
        $periode->update(['is_active' => true]);

        return redirect()->route('periode.index')->with('success', 'Periode ' . $periode->nama . ' diaktifkan.');
    }
}

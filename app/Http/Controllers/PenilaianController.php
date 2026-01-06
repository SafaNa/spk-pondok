<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penilaian = \App\Models\Penilaian::with(['santri', 'kriteria', 'subkriteria'])->latest()->paginate(10);
        $periodes = \App\Models\Periode::orderBy('created_at', 'desc')->get();
        $santriList = \App\Models\Santri::orderBy('nama')->get();
        return view('v2.penilaian.index', compact('penilaian', 'periodes', 'santriList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kriteria = \App\Models\Kriteria::with('subkriteria')->get();
        $santri = \App\Models\Santri::where('status', 'aktif')->orderBy('nama')->get();
        // Use active periode logic similar to PerhitunganController
        $activePeriode = \App\Models\Periode::where('is_active', true)->first();
        $periodes = \App\Models\Periode::orderBy('created_at', 'desc')->get(); // For dropdown if needed

        return view('v2.penilaian.form', compact('kriteria', 'santri', 'activePeriode', 'periodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

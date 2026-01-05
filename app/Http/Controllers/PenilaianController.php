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
        return view('penilaian.index', compact('penilaian'));
    }

    public function indexV2()
    {
        $penilaian = \App\Models\Penilaian::with(['santri', 'kriteria', 'subkriteria'])->latest()->paginate(10);
        $periodes = \App\Models\Periode::orderBy('created_at', 'desc')->get();
        $santriList = \App\Models\Santri::orderBy('nama')->get();
        return view('penilaian-v2', compact('penilaian', 'periodes', 'santriList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

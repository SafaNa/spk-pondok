<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departemen = Departemen::withCount('jenisPelanggaran')->orderBy('kode_departemen')->paginate(10);
        return view('v2.departemen.index', compact('departemen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('v2.departemen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_departemen' => 'required|unique:departemen,kode_departemen|max:20',
            'nama_departemen' => 'required|max:255',
            'singkatan' => 'required|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $departemen = Departemen::create($validated);

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departemen $departemen)
    {
        $departemen->load(['jenisPelanggaran.kategoriPelanggaran', 'users']);
        return view('v2.departemen.detail', compact('departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departemen $departemen)
    {
        return view('v2.departemen.edit', compact('departemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'kode_departemen' => 'required|unique:departemen,kode_departemen,' . $departemen->id,
            'nama_departemen' => 'required',
            'singkatan' => 'required',
            'keterangan' => 'nullable'
        ]);

        $departemen->update($validated);

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil diupdate');
    }

    /**
     * Show create form for V2
     */
    public function createV2()
    {
        return view('v2.departemen.create');
    }

    /**
     * Show edit form for V2
     */
    public function editV2(Departemen $departemen)
    {
        return view('v2.departemen.edit', compact('departemen'));
    }

    /**
     * Display the specified resource for V2
     */
    public function showV2(Departemen $departemen)
    {
        $departemen->load(['jenisPelanggaran.kategoriPelanggaran']);
        return view('v2.departemen.detail', compact('departemen'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departemen $departemen)
    {
        // Check if department has violations
        if ($departemen->jenisPelanggaran()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Tidak dapat menghapus departemen yang memiliki jenis pelanggaran');
        }

        // Check if department has users
        if ($departemen->users()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Tidak dapat menghapus departemen yang memiliki pengurus');
        }

        $departemen->delete();

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil dihapus');
    }
}

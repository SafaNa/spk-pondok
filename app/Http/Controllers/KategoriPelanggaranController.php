<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;

class KategoriPelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = KategoriPelanggaran::withCount('jenisPelanggaran')->orderBy('kode_kategori')->paginate(10);
        return view('v2.kategori-pelanggaran.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('v2.kategori-pelanggaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kategori' => 'required|unique:kategori_pelanggaran,kode_kategori|max:10',
            'nama_kategori' => 'required|max:255',
            'bobot_poin' => 'required|integer|min:0',
        ]);

        KategoriPelanggaran::create($validated);

        return redirect()->route('kategori-pelanggaran.index')->with('success', 'Kategori pelanggaran berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriPelanggaran $kategoriPelanggaran)
    {
        return view('v2.kategori-pelanggaran.edit', compact('kategoriPelanggaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPelanggaran $kategoriPelanggaran)
    {
        $validated = $request->validate([
            'kode_kategori' => 'required|max:10|unique:kategori_pelanggaran,kode_kategori,' . $kategoriPelanggaran->id,
            'nama_kategori' => 'required|max:255',
            'bobot_poin' => 'required|integer|min:0',
        ]);

        $kategoriPelanggaran->update($validated);

        return redirect()->route('kategori-pelanggaran.index')->with('success', 'Kategori pelanggaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPelanggaran $kategoriPelanggaran)
    {
        if ($kategoriPelanggaran->jenisPelanggaran()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki jenis pelanggaran');
        }

        $kategoriPelanggaran->delete();

        return redirect()->route('kategori-pelanggaran.index')->with('success', 'Kategori pelanggaran berhasil dihapus');
    }
}

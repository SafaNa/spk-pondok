<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisPelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = JenisPelanggaran::with(['departemen', 'kategoriPelanggaran']);

        if ($user->isPengurusDepartemen()) {
            $query->where('departemen_id', $user->departemen_id);
        }

        $jenisPelanggaran = $query->orderBy('kode_pelanggaran')->paginate(10);

        return view('v2.jenis-pelanggaran.index', compact('jenisPelanggaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect to department-specific create if accessed directly
        // Or implement generic create if needed. For now, let's keep it department-scoped if possible
        // But since the menu is generic, we might need a generic create or a redirect.
        // Let's implement a generic create that allows selecting department (if admin).

        $kategori = KategoriPelanggaran::all();
        $departemen = \App\Models\Departemen::all(); // For admin to select

        return view('v2.jenis-pelanggaran.create', compact('kategori', 'departemen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'kategori_pelanggaran_id' => 'required|exists:kategori_pelanggaran,id',
            'kode_pelanggaran' => 'required|max:20|unique:jenis_pelanggaran,kode_pelanggaran',
            'nama_pelanggaran' => 'required|max:255',
            'sanksi_default' => 'required|string',
            'is_active' => 'boolean',
            'deskripsi' => 'nullable|string'
        ]);

        // Permission check
        if (Auth::user()->isPengurusDepartemen() && $validated['departemen_id'] != Auth::user()->departemen_id) {
            abort(403);
        }

        JenisPelanggaran::create($validated);

        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisPelanggaran $jenisPelanggaran)
    {
        return view('v2.jenis-pelanggaran.detail', compact('jenisPelanggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisPelanggaran $jenisPelanggaran)
    {
        $user = Auth::user();

        if ($user->isPengurusDepartemen() && $jenisPelanggaran->departemen_id != $user->departemen_id) {
            abort(403);
        }

        $kategori = KategoriPelanggaran::all();
        $departemen = \App\Models\Departemen::all();

        return view('v2.jenis-pelanggaran.edit', compact('jenisPelanggaran', 'kategori', 'departemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisPelanggaran $jenisPelanggaran)
    {
        $user = Auth::user();

        if ($user->isPengurusDepartemen() && $jenisPelanggaran->departemen_id != $user->departemen_id) {
            abort(403);
        }

        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'kategori_pelanggaran_id' => 'required|exists:kategori_pelanggaran,id',
            'kode_pelanggaran' => 'required|max:20|unique:jenis_pelanggaran,kode_pelanggaran,' . $jenisPelanggaran->id,
            'nama_pelanggaran' => 'required|max:255',
            'sanksi_default' => 'required|string',
            'is_active' => 'boolean',
            'deskripsi' => 'nullable|string'
        ]);

        // Permission check for target department
        if (Auth::user()->isPengurusDepartemen() && $validated['departemen_id'] != Auth::user()->departemen_id) {
            abort(403);
        }

        $jenisPelanggaran->update($validated);

        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        $user = Auth::user();

        if ($user->isPengurusDepartemen() && $jenisPelanggaran->departemen_id != $user->departemen_id) {
            abort(403);
        }

        if ($jenisPelanggaran->riwayatPelanggaran()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis pelanggaran yang sudah digunakan dalam riwayat');
        }

        $jenisPelanggaran->delete();

        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil dihapus');
    }
}

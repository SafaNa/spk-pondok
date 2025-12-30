<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubkriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Kriteria $kriteria)
    {
        $subkriteria = $kriteria->subkriteria()->orderBy('nilai', 'desc')->paginate(10);
        return view('subkriteria.index', compact('kriteria', 'subkriteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Kriteria $kriteria)
    {
        return view('subkriteria.create', compact('kriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $kriteria->subkriteria()->create([
                'nama_subkriteria' => $validated['nama_subkriteria'],
                'nilai' => $validated['nilai'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('kriteria.subkriteria.index', $kriteria->id)
                ->with('success', 'Subkriteria berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan subkriteria: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriteria, Subkriteria $subkriteria)
    {
        return view('subkriteria.show', compact('kriteria', 'subkriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria, Subkriteria $subkriteria)
    {
        return view('subkriteria.edit', compact('kriteria', 'subkriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriteria, Subkriteria $subkriteria)
    {
        $validated = $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $subkriteria->update([
                'nama_subkriteria' => $validated['nama_subkriteria'],
                'nilai' => $validated['nilai'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('kriteria.subkriteria.index', $kriteria->id)
                ->with('success', 'Subkriteria berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui subkriteria: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria, Subkriteria $subkriteria)
    {
        try {
            DB::beginTransaction();
            
            // Check if subkriteria is used in penilaian
            if ($subkriteria->penilaian()->exists()) {
                return back()->with('error', 'Tidak dapat menghapus subkriteria karena sudah digunakan dalam penilaian.');
            }
            
            $subkriteria->delete();
            
            DB::commit();
            return redirect()->route('kriteria.subkriteria.index', $kriteria->id)
                ->with('success', 'Subkriteria berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus subkriteria: ' . $e->getMessage());
        }
    }
}

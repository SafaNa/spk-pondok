<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::latest()->get();
        return view('santri.index', compact('santri'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:santri,nis|max:20',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'nama_ortu' => 'required|string|max:100',
            'no_hp_ortu' => 'required|string|max:20',
            'status' => 'required|in:aktif,non-aktif,lulus,drop-out',
        ]);

        Santri::create($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }

    public function show(Santri $santri)
    {
        return view('santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nis' => [
                'required',
                'max:20',
                Rule::unique('santri')->ignore($santri->id)
            ],
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'nama_ortu' => 'required|string|max:100',
            'no_hp_ortu' => 'required|string|max:20',
            'status' => 'required|in:aktif,non-aktif,lulus,drop-out',
        ]);

        $santri->update($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();
        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus');
    }
}
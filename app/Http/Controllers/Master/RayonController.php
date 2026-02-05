<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Rayon;
use Illuminate\Http\Request;

class RayonController extends Controller
{
    public function index()
    {
        $rayons = Rayon::orderBy('name')->get();
        return view('rayons.index', compact('rayons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:rayons,name',
        ]);

        Rayon::create($validated);
        return redirect()->route('rayons.index')->with('success', 'Rayon berhasil ditambahkan');
    }

    public function update(Request $request, Rayon $rayon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:rayons,name,' . $rayon->id,
        ]);

        $rayon->update($validated);
        return redirect()->route('rayons.index')->with('success', 'Rayon berhasil diperbarui');
    }

    public function destroy(Rayon $rayon)
    {
        $rayon->delete();
        return redirect()->route('rayons.index')->with('success', 'Rayon berhasil dihapus');
    }
}

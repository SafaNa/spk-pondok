<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MemorizationType;
use Illuminate\Http\Request;

class MemorizationTypeController extends Controller
{
    public function index()
    {
        $types = MemorizationType::orderByRaw("FIELD(education_level, 'MTS', 'MA', 'PT')")->orderBy('day')->get()->groupBy('education_level');
        return view('memorization_types.index', compact('types'));
    }
    public function create()
    {
        return view('memorization_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'education_level' => 'required|in:MTS,MA,PT,Other',
            'day' => 'required|integer|min:1',
            'target_description' => 'required|string',
        ]);

        MemorizationType::create($validated);

        return redirect()->route('memorization-types.index')
            ->with('success', 'Ketentuan hafalan berhasil ditambahkan.');
    }

    public function edit(MemorizationType $memorization_type)
    {
        return view('memorization_types.edit', compact('memorization_type'));
    }

    public function update(Request $request, MemorizationType $memorization_type)
    {
        $validated = $request->validate([
            'target_description' => 'required|string',
        ]);

        $memorization_type->update($validated);

        return redirect()->route('memorization-types.index')
            ->with('success', 'Ketentuan hafalan berhasil diperbarui.');
    }

    public function destroy(MemorizationType $memorization_type)
    {
        $memorization_type->delete();

        return redirect()->route('memorization-types.index')
            ->with('success', 'Ketentuan hafalan berhasil dihapus.');
    }
}

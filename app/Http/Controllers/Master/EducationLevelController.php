<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\EducationLevel;
use Illuminate\Http\Request;

class EducationLevelController extends Controller
{
    public function index()
    {
        $levels = EducationLevel::all();
        return view('education_levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:formal,religious',
        ]);

        EducationLevel::create($validated);

        return redirect()->route('education-levels.index')->with('success', 'Jenjang pendidikan berhasil ditambahkan');
    }

    public function update(Request $request, EducationLevel $educationLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:formal,religious',
        ]);

        $educationLevel->update($validated);

        return redirect()->route('education-levels.index')->with('success', 'Jenjang pendidikan berhasil diperbarui');
    }

    public function destroy(EducationLevel $educationLevel)
    {
        $educationLevel->delete();
        return redirect()->route('education-levels.index')->with('success', 'Jenjang pendidikan berhasil dihapus');
    }
}

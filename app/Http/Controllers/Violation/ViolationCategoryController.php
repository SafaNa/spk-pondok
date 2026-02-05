<?php

namespace App\Http\Controllers\Violation;

use App\Http\Controllers\Controller;
use App\Models\Violation\ViolationCategory;
use Illuminate\Http\Request;

class ViolationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $violationCategories = ViolationCategory::withCount('violationTypes')->orderBy('points')->paginate(10);
        return view('violation-categories.index', compact('violationCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('violation-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'points' => 'required|integer|min:0',
        ]);

        ViolationCategory::create($validated);

        return redirect()->route('violation-categories.index')->with('success', 'Kategori pelanggaran berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $violationCategory = ViolationCategory::findOrFail($id);
        return view('violation-categories.edit', compact('violationCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $violationCategory = ViolationCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'points' => 'required|integer|min:0',
        ]);

        $violationCategory->update($validated);

        return redirect()->route('violation-categories.index')->with('success', 'Kategori pelanggaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $violationCategory = ViolationCategory::findOrFail($id);

        if ($violationCategory->violationTypes()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki jenis pelanggaran');
        }

        $violationCategory->delete();

        return redirect()->route('violation-categories.index')->with('success', 'Kategori pelanggaran berhasil dihapus');
    }
}

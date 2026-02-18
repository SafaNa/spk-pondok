<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\AcademicYear;
use App\Models\Master\Period;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::latest()->get();
        return view('academic_years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('academic_years.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'stage1_deadline' => 'nullable|date',
            'stage2_deadline' => 'nullable|date',
        ]);

        AcademicYear::create($validated);
        return redirect()->route('academic-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic_years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'stage1_deadline' => 'nullable|date',
            'stage2_deadline' => 'nullable|date',
        ]);

        $academicYear->update($validated);
        return redirect()->route('academic-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        return redirect()->route('academic-years.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function toggleStatus(AcademicYear $academicYear)
    {
        $newStatus = $academicYear->status == 'active' ? 'inactive' : 'active';

        if ($newStatus == 'active') {
            // Deactivate all others first
            AcademicYear::where('status', 'active')->update(['status' => 'inactive']);
        }

        $academicYear->update(['status' => $newStatus]);

        $message = $newStatus == 'active' ? 'Tahun ajaran diaktifkan.' : 'Tahun ajaran dinonaktifkan.';
        return redirect()->back()->with('success', $message);
    }
}

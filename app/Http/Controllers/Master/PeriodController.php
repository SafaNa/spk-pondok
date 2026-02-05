<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use App\Models\Master\AcademicYear;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::with('academicYear')->latest()->paginate(10);
        $activePeriode = Period::where('is_active', true)->first();
        $academicYears = AcademicYear::where('status', 'active')->get();
        $activeAcademicYear = AcademicYear::where('status', 'active')->first();
        return view('periods.index', compact('periods', 'activePeriode', 'academicYears', 'activeAcademicYear'));
    }

    public function create()
    {
        $academicYears = AcademicYear::where('status', 'active')->get();
        return view('periods.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        // Automatically use the active academic year
        $activeAcademicYear = AcademicYear::where('status', 'active')->first();

        if (!$activeAcademicYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        $validated['academic_year_id'] = $activeAcademicYear->id;

        Period::create($validated);

        return redirect()->route('periods.index')
            ->with('success', 'Periode berhasil ditambahkan');
    }

    public function edit(Period $period)
    {
        $academicYears = AcademicYear::all();
        return view('periods.edit', compact('period', 'academicYears'));
    }

    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        $period->update($validated);

        return redirect()->route('periods.index')
            ->with('success', 'Periode berhasil diperbarui');
    }

    public function destroy(Period $period)
    {
        $period->delete();
        return redirect()->route('periods.index')
            ->with('success', 'Periode berhasil dihapus');
    }

    public function activate(Period $period)
    {
        Period::where('is_active', true)->update(['is_active' => false]);
        $period->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Periode berhasil diaktifkan');
    }
}

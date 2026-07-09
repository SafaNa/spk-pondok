<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Licensing\StudentLicense;
use App\Models\Master\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenseController extends Controller
{
    public function index()
    {
        $guardian   = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        $licenses = StudentLicense::with('student')
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->paginate(10);

        return view('guardian.licenses.index', compact('guardian', 'licenses'));
    }

    public function create()
    {
        $guardian   = Auth::guard('guardian')->user();
        $students   = $guardian->students()->get();
        $activeYear = AcademicYear::where('status', 'active')->first();

        return view('guardian.licenses.create', compact('guardian', 'students', 'activeYear'));
    }

    public function store(Request $request)
    {
        $guardian = Auth::guard('guardian')->user();

        $request->validate([
            'student_id'       => 'required|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'description'      => 'required|string|max:500',
        ]);

        $studentIds = $guardian->students()->pluck('students.id');
        if (!$studentIds->contains($request->student_id)) {
            abort(403, 'Student does not belong to this guardian.');
        }

        StudentLicense::create([
            'student_id'         => $request->student_id,
            'academic_year_id'   => $request->academic_year_id,
            'type'               => 'individual',
            'start_date'         => $request->start_date,
            'end_date'           => $request->end_date,
            'description'        => $request->description,
            'status'             => 'pending',
            'memorization_check' => false,
        ]);

        return redirect()->route('guardian.licenses.index')
            ->with('success', 'License request submitted successfully. Awaiting officer validation.');
    }
}

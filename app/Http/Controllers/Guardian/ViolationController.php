<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Violation\ViolationRecord;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    public function index()
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('id');

        $violations = ViolationRecord::with(['student', 'violationType.category', 'violationType.department', 'academicYear'])
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->paginate(15);

        return view('guardian.violations.index', compact('guardian', 'violations'));
    }

    public function show(ViolationRecord $violation)
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('id');

        abort_unless($studentIds->contains($violation->student_id), 403);

        $violation->load(['student.rayon', 'student.room', 'student.formalEducation', 'student.religiousEducation', 'violationType.category', 'violationType.department', 'academicYear', 'creator']);

        return view('guardian.violations.show', compact('guardian', 'violation'));
    }
}

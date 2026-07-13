<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Licensing\LeaveCategory;
use App\Models\Licensing\LeaveReason;
use App\Models\Licensing\StudentLicense;
use App\Models\Master\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    public function index()
    {
        /** @var Guardian $guardian */
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
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $students   = $guardian->students()->get();
        $activeYear = AcademicYear::where('status', 'active')->first();
        $categories = LeaveCategory::orderBy('order')->get();

        return view('guardian.licenses.create', compact('guardian', 'students', 'activeYear', 'categories'));
    }

    public function categoryReasons(LeaveCategory $leaveCategory)
    {
        return response()->json(
            $leaveCategory->reasons()->orderBy('order')->get(['id', 'reason'])
        );
    }

    public function store(Request $request)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif. Hubungi admin pondok.');
        }

        $request->validate([
            'student_id'      => 'required|string',
            'leave_reason_id' => 'required|exists:leave_reasons,id',
            'description'     => 'nullable|string|max:500',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'is_emergency'    => 'boolean',
            'attachment'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $studentIds = $guardian->students()->pluck('students.id');
        if (!$studentIds->contains($request->student_id)) {
            abort(403, 'Santri tidak terdaftar untuk akun ini.');
        }

        $reason          = LeaveReason::find($request->leave_reason_id);
        $leaveCategoryId = $reason?->leave_category_id;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('license-attachments', 'public');
        }

        StudentLicense::create([
            'student_id'        => $request->student_id,
            'academic_year_id'  => $activeYear->id,
            'leave_category_id' => $leaveCategoryId,
            'leave_reason_id'   => $request->leave_reason_id,
            'type'              => 'individual',
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'description'       => $request->description,
            'attachment'        => $attachmentPath,
            'status'            => 'pending',
            'is_emergency'      => $request->has('is_emergency'),
            'submitted_at'      => now(),
            'source'            => 'guardian',
            'creator_id'        => Auth::guard('guardian')->id(),
            'creator_type'      => \App\Models\Guardian::class,
        ]);

        return redirect()->route('guardian.licenses.index')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu validasi petugas.');
    }
}

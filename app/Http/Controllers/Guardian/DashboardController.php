<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Licensing\StudentLicense;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $students   = $guardian->students()->with(['rayon', 'room', 'formalEducation', 'religiousEducation'])->get();
        $studentIds = $students->pluck('id');

        $activeYear = \App\Models\Master\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $totalLicenses  = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->count();
        $approvedCount  = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->where('status', 'approved')->count();
        $pendingCount   = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->where('status', 'pending')->count();
        $rejectedCount  = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->where('status', 'rejected')->count();

        // Tambahkan pengajuan perpanjangan ke perhitungan
        $extensions = \App\Models\Licensing\LicenseExtension::whereHas('studentLicense', function($q) use ($studentIds, $activeYearId) {
            $q->whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId);
        })->get();

        $extTotal    = $extensions->count();
        $extApproved = $extensions->where('status', 'approved')->count();
        $extPending  = $extensions->where('status', 'pending')->count();
        $extRejected = $extensions->where('status', 'rejected')->count();

        $recentLicenses = StudentLicense::with(['student', 'extensions'])
            ->whereIn('student_id', $studentIds)
            ->where('academic_year_id', $activeYearId)
            ->latest()
            ->limit(5)
            ->get();

        return view('guardian.dashboard', compact(
            'guardian', 'students', 'recentLicenses',
            'totalLicenses', 'approvedCount', 'pendingCount', 'rejectedCount',
            'extTotal', 'extApproved', 'extPending', 'extRejected'
        ));
    }
}

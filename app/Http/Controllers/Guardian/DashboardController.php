<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Licensing\StudentLicense;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $guardian   = Auth::guard('guardian')->user();
        $students   = $guardian->students()->with(['rayon', 'room', 'formalEducation', 'religiousEducation'])->get();
        $studentIds = $students->pluck('id');

        $totalLicenses  = StudentLicense::whereIn('student_id', $studentIds)->count();
        $approvedCount  = StudentLicense::whereIn('student_id', $studentIds)->where('status', 'approved')->count();
        $pendingCount   = StudentLicense::whereIn('student_id', $studentIds)->where('status', 'pending')->count();
        $rejectedCount  = StudentLicense::whereIn('student_id', $studentIds)->where('status', 'rejected')->count();

        $recentLicenses = StudentLicense::with('student')
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->limit(5)
            ->get();

        return view('guardian.dashboard', compact(
            'guardian', 'students', 'recentLicenses',
            'totalLicenses', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }
}

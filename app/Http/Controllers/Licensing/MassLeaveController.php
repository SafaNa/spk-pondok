<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Licensing\MassLeave;
use App\Models\Licensing\MassLeaveStudent;
use App\Models\Master\AcademicYear;
use App\Models\Master\Student;
use App\Models\Violation\ViolationRecord;
use Illuminate\Support\Facades\Auth;

class MassLeaveController extends Controller
{
    public function index()
    {
        $leaves = MassLeave::withCount('students')->latest()->get();
        return view('licensing.mass-leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('licensing.mass-leaves.create');
    }

    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        MassLeave::create([
            'academic_year_id' => $activeYear->id,
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.mass-leaves.index')->with('success', 'Event Liburan Massal berhasil dibuat.');
    }

    public function show(MassLeave $mass_leaf)
    {
        $students = $mass_leaf->students()->with('student.rayon', 'student.room')->get();
        return view('licensing.mass-leaves.show', compact('mass_leaf', 'students'));
    }

    public function checkout(MassLeave $mass_leaf)
    {
        if (!$mass_leaf->is_active) {
            return redirect()->route('admin.mass-leaves.index')->with('error', 'Event ini sudah tidak aktif.');
        }
        
        $students = Student::with('rayon', 'room')->orderBy('name')->get();
        return view('licensing.mass-leaves.checkout', compact('mass_leaf', 'students'));
    }

    public function processCheckout(Request $request, MassLeave $mass_leaf)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $studentId = $request->student_id;
        $student = Student::find($studentId);

        // 1. Cek apakah sudah pernah checkout
        $alreadyExists = MassLeaveStudent::where('mass_leave_id', $mass_leaf->id)
            ->where('student_id', $studentId)
            ->first();

        if ($alreadyExists) {
            return response()->json([
                'success' => false,
                'message' => "Ananda {$student->name} sudah di-ACC sebelumnya pada event liburan ini."
            ], 400);
        }

        // 2. Cek Tanggungan Pelanggaran
        $pendingViolations = ViolationRecord::where('student_id', $studentId)
            ->where('sanction_status', 'pending')
            ->count();

        if ($pendingViolations > 0) {
            return response()->json([
                'success' => false,
                'message' => "Ananda {$student->name} masih memiliki {$pendingViolations} tanggungan pelanggaran yang belum lunas. Selesaikan dulu di menu Pelanggaran!"
            ], 400);
        }

        // 3. Proses ACC (Checkout)
        MassLeaveStudent::create([
            'mass_leave_id' => $mass_leaf->id,
            'student_id' => $studentId,
            'checked_out_at' => now(),
            'checked_out_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Izin Liburan untuk Ananda {$student->name} berhasil di-ACC."
        ]);
    }
}

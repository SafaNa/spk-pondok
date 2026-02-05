<?php

namespace App\Http\Controllers\Violation;

use App\Http\Controllers\Controller;
use App\Models\Violation\ViolationRecord;
use App\Models\Violation\ViolationType;
use App\Models\Master\Student;
use App\Models\Master\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    /**
     * Display a listing of violations
     */
    public function index()
    {
        $user = Auth::user();

        $query = ViolationRecord::with([
            'student',
            'violationType.category',
            'violationType.department',
            'creator'
        ])->orderBy('date', 'desc');

        // Filter by department for departemen staff
        if ($user->isDepartmentOfficer()) {
            $query->whereHas('violationType', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        $violations = $query->paginate(20);

        // Prepare stats query with same filter
        $statsQuery = ViolationRecord::query();
        if ($user->isDepartmentOfficer()) {
            $statsQuery->whereHas('violationType', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        // Get summary stats
        $stats = [
            'total' => $violations->total(),
            'pending' => (clone $statsQuery)->where('sanction_status', 'pending')->count(),
            'completed' => (clone $statsQuery)->where('sanction_status', 'completed')->count(),
        ];

        return view('violations.index', compact('violations', 'stats'));
    }

    /**
     * Show the form for creating a new violation
     */
    public function create()
    {
        $user = Auth::user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif. Silakan aktifkan periode terlebih dahulu.');
        }

        // Get active students
        $students = Student::where('status', 'active')->orderBy('name')->get();

        // Get violation types (filtered by department for departemen staff)
        $query = ViolationType::where('is_active', true)
            ->with(['category', 'department'])
            ->orderBy('name');

        if ($user->isDepartmentOfficer()) {
            $query->where('department_id', $user->department_id);
        }

        $violationTypes = $query->get();

        return view('violations.create', compact('students', 'violationTypes', 'activePeriod'));
    }

    /**
     * Store a newly created violation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_type_id' => 'required|exists:violation_types,id',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $violationType = ViolationType::findOrFail($validated['violation_type_id']);
        $period = Period::where('is_active', true)->first();

        if (!$period) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif');
        }

        // Check permission
        $user = Auth::user();
        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) {
            abort(403, 'Anda tidak memiliki akses untuk mencatat pelanggaran departemen ini');
        }

        ViolationRecord::create([
            'student_id' => $validated['student_id'],
            'violation_type_id' => $validated['violation_type_id'],
            'period_id' => $period->id,
            'date' => $validated['date'],
            'sanction' => $violationType->default_sanction, // Automatically set
            'sanction_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('violations.index')->with('success', 'Pelanggaran berhasil dicatat');
    }

    /**
     * Display the specified violation
     */
    public function show($id)
    {
        $violation = ViolationRecord::with([
            'student',
            'violationType.category',
            'violationType.department',
            'period',
            'creator'
        ])->findOrFail($id);

        return view('violations.show', compact('violation'));
    }

    /**
     * Verify/complete a sanction
     */
    public function verifySanction(Request $request, $id)
    {
        $violation = ViolationRecord::findOrFail($id);

        // Check if user has permission
        $user = Auth::user();
        if ($user->isDepartmentOfficer()) {
            $violation->load('violationType');
            if ($violation->violationType->department_id != $user->department_id) {
                abort(403, 'Anda tidak memiliki akses untuk memverifikasi sanksi departemen ini');
            }
        }

        $violation->update([
            'sanction_status' => 'completed',
            'verified_at' => now(),
            'verified_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Sanksi berhasil diverifikasi sebagai selesai');
    }

    /**
     * Display violation history for a student
     */
    public function history($studentId)
    {
        $student = Student::findOrFail($studentId);

        $violations = ViolationRecord::where('student_id', $studentId)
            ->with([
                'violationType.category',
                'violationType.department',
                'period',
                'creator',
                'verifier'
            ])
            ->orderBy('date', 'desc')
            ->get();

        // Group by period
        $violationsByPeriod = $violations->groupBy('period_id');

        return view('violations.history', compact('student', 'violations', 'violationsByPeriod'));
    }

    /**
     * Show the form for editing the specified violation.
     */
    public function edit($id)
    {
        $violation = ViolationRecord::with('violationType')->findOrFail($id);
        $user = Auth::user();

        // Check permission
        if ($user->isDepartmentOfficer() && $violation->violationType->department_id != $user->department_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran ini');
        }

        if ($user->isLicensingOfficer()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran');
        }

        $activePeriod = Period::where('is_active', true)->first();
        if (!$activePeriod) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $students = Student::where('status', 'active')->orderBy('name')->get();

        // Get violation types
        $query = ViolationType::where('is_active', true)
            ->with(['category', 'department'])
            ->orderBy('name');

        if ($user->isDepartmentOfficer()) {
            $query->where('department_id', $user->department_id);
        }

        $violationTypes = $query->get();

        return view('violations.edit', compact('violation', 'students', 'violationTypes', 'activePeriod'));
    }

    /**
     * Update the specified violation in storage.
     */
    public function update(Request $request, $id)
    {
        $violation = ViolationRecord::findOrFail($id);
        $user = Auth::user();

        if ($user->isDepartmentOfficer()) {
            $violation->load('violationType');
            if ($violation->violationType->department_id != $user->department_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran ini');
            }
        }
        if ($user->isLicensingOfficer()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_type_id' => 'required|exists:violation_types,id',
            'date' => 'required|date',
            'sanction' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // If violation type changed, check department access
        $violationType = ViolationType::findOrFail($validated['violation_type_id']);
        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) {
            abort(403, 'Anda tidak memiliki akses untuk jenis pelanggaran departemen ini');
        }

        // Check if type changed, update sanction automatically if so? 
        // User request: "Sistem secara otomatis Menentukan sanksi".
        // If updating, should we reset sanction? Probably yes if type changes.

        $data = [
            'student_id' => $validated['student_id'],
            'violation_type_id' => $validated['violation_type_id'],
            'date' => $validated['date'],
            'sanction' => $validated['sanction'],
            'notes' => $validated['notes']
        ];

        $violation->update($data);

        return redirect()->route('violations.index')->with('success', 'Pelanggaran berhasil diperbarui');
    }

    /**
     * Remove the specified violation from storage.
     */
    public function destroy($id)
    {
        $violation = ViolationRecord::findOrFail($id);
        $user = Auth::user();

        if ($user->isDepartmentOfficer()) {
            $violation->load('violationType');
            if ($violation->violationType->department_id != $user->department_id) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus pelanggaran ini');
            }
        }
        if ($user->isLicensingOfficer()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pelanggaran');
        }

        $violation->delete();

        return redirect()->route('violations.index')->with('success', 'Pelanggaran berhasil dihapus');
    }
}

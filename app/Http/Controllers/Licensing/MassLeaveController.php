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
        MassLeave::closeExpiredEvents();
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
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.after_or_equal' => 'Tanggal mulai libur tidak boleh sebelum tanggal hari ini.',
            'end_date.after_or_equal'   => 'Tanggal kembali tidak boleh lebih awal dari tanggal mulai libur.'
        ]);

        $overlap = MassLeave::where('status', '!=', 'completed')
            ->where('start_date', '<=', $validated['end_date'])
            ->where('end_date', '>=', $validated['start_date'])
            ->first();

        if ($overlap) {
            return back()->withInput()->withErrors([
                'start_date' => "Tanggal libur bentrok dengan event lain yang sudah ada: {$overlap->title} (" . \Carbon\Carbon::parse($overlap->start_date)->format('d/m/Y') . " s/d " . \Carbon\Carbon::parse($overlap->end_date)->format('d/m/Y') . ")."
            ]);
        }

        // Cek apakah saat ini sedang ada event yang berlangsung
        $hasOngoing = MassLeave::all()->contains(fn($l) => $l->isOngoing());
        $status = $hasOngoing ? 'inactive' : 'active';
        if ($status === 'active') {
            MassLeave::where('status', 'active')->update(['status' => 'inactive']);
        }

        MassLeave::create([
            'academic_year_id' => $activeYear->id,
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.mass-leaves.index')->with('success', 'Event Liburan Massal berhasil dibuat.');
    }

    public function show(MassLeave $mass_leaf)
    {
        MassLeave::closeExpiredEvents();
        $mass_leaf->refresh();
        $students = $mass_leaf->students()->with('student.rayon', 'student.room')->get();

        // Statistik Pre-Checkout (Kesiapan Pulang)
        $activeAcademicYearId = $mass_leaf->academic_year_id;
        $totalActiveStudents = Student::where('status', 'active')->count();
        $blockedStudentsCount = Student::where('status', 'active')
            ->whereHas('pendingViolations', fn($q) => $q->where('academic_year_id', $activeAcademicYearId))
            ->count();
        $eligibleStudentsCount = max(0, $totalActiveStudents - $blockedStudentsCount);

        // Daftar santri yang tertahan
        $blockedStudents = Student::where('status', 'active')
            ->whereHas('pendingViolations', fn($q) => $q->where('academic_year_id', $activeAcademicYearId))
            ->with([
                'rayon', 'room',
                'pendingViolations' => fn($q) => $q->where('academic_year_id', $activeAcademicYearId),
                'pendingViolations.violationType'
            ])
            ->orderBy('name')
            ->get();

        // Statistik Realisasi
        $totalCheckedOut = $students->count();
        $returnedCount = $students->whereNotNull('actual_return_date')->count();
        $notReturnedCount = $students->whereNull('actual_return_date')->count();

        return view('licensing.mass-leaves.show', compact(
            'mass_leaf', 'students',
            'totalActiveStudents', 'blockedStudentsCount', 'eligibleStudentsCount', 'blockedStudents',
            'totalCheckedOut', 'returnedCount', 'notReturnedCount'
        ));
    }

    public function checkout(MassLeave $mass_leaf)
    {
        MassLeave::closeExpiredEvents();
        $mass_leaf->refresh();
        if (!$mass_leaf->is_active) {
            return redirect()->route('admin.mass-leaves.index')->with('error', 'Event ini sudah tidak aktif.');
        }
        
        $students = Student::with('rayon', 'room')->orderBy('name')->get();

        // Statistik Pre-Checkout (Kesiapan Pulang)
        $activeAcademicYearId = $mass_leaf->academic_year_id;
        $totalActiveStudents = Student::where('status', 'active')->count();
        $blockedStudentsCount = Student::where('status', 'active')
            ->whereHas('pendingViolations', fn($q) => $q->where('academic_year_id', $activeAcademicYearId))
            ->count();
        $eligibleStudentsCount = max(0, $totalActiveStudents - $blockedStudentsCount);

        // Daftar santri yang tertahan
        $blockedStudents = Student::where('status', 'active')
            ->whereHas('pendingViolations', fn($q) => $q->where('academic_year_id', $activeAcademicYearId))
            ->with([
                'rayon', 'room',
                'pendingViolations' => fn($q) => $q->where('academic_year_id', $activeAcademicYearId),
                'pendingViolations.violationType.department',
                'pendingViolations.violationType.category'
            ])
            ->orderBy('name')
            ->get();

        return view('licensing.mass-leaves.checkout', compact(
            'mass_leaf', 'students',
            'totalActiveStudents', 'blockedStudentsCount', 'eligibleStudentsCount',
            'blockedStudents'
        ));
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

    public function forceCheckoutWithSanction(Request $request, MassLeave $mass_leaf)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $studentId = $request->student_id;
        $student = Student::findOrFail($studentId);

        // 1. Cek apakah sudah pernah checkout
        $alreadyExists = MassLeaveStudent::where('mass_leave_id', $mass_leaf->id)
            ->where('student_id', $studentId)
            ->first();

        if ($alreadyExists) {
            return redirect()->back()->with('error', "Ananda {$student->name} sudah di-ACC sebelumnya pada event liburan ini.");
        }

        // 2. Selesaikan (Verify) seluruh tanggungan pelanggaran aktif santri ini (tahun ajaran event ini)
        ViolationRecord::where('student_id', $studentId)
            ->where('sanction_status', 'pending')
            ->where('academic_year_id', $mass_leaf->academic_year_id)
            ->update([
                'sanction_status' => 'completed',
                'verified_at' => now(),
                'verified_by' => Auth::id()
            ]);

        // 3. Proses ACC (Checkout)
        MassLeaveStudent::create([
            'mass_leave_id' => $mass_leaf->id,
            'student_id' => $studentId,
            'checked_out_at' => now(),
            'checked_out_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "Sanksi Ananda {$student->name} telah ditandai selesai & Izin Kepulangan berhasil di-ACC.");
    }

    public function bulkCheckout(MassLeave $mass_leaf)
    {
        if (!$mass_leaf->is_active) {
            return redirect()->route('admin.mass-leaves.index')->with('error', 'Event ini sudah tidak aktif.');
        }

        // 1. Ambil seluruh santri aktif
        $activeStudents = Student::where('status', 'active')->get();

        // 2. Ambil ID santri yang sudah pernah di-ACC pada event ini
        $alreadyCheckedOutIds = MassLeaveStudent::where('mass_leave_id', $mass_leaf->id)
            ->pluck('student_id')
            ->toArray();

        // 3. Ambil ID santri yang memiliki tanggungan pelanggaran aktif (tahun ajaran event ini)
        $blockedStudentIds = ViolationRecord::where('sanction_status', 'pending')
            ->where('academic_year_id', $mass_leaf->academic_year_id)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $successCount = 0;
        $now = now();
        $userId = Auth::id();

        foreach ($activeStudents as $student) {
            if (in_array($student->id, $alreadyCheckedOutIds) || in_array($student->id, $blockedStudentIds)) {
                continue;
            }

            MassLeaveStudent::create([
                'mass_leave_id' => $mass_leaf->id,
                'student_id' => $student->id,
                'checked_out_at' => $now,
                'checked_out_by' => $userId,
            ]);

            $successCount++;
        }

        $blockedCount = count(array_intersect($activeStudents->pluck('id')->toArray(), $blockedStudentIds));

        // Catat waktu dan petugas yang menekan tombol ACC Massal
        $mass_leaf->update([
            'bulk_checkout_at' => now(),
            'bulk_checkout_by' => Auth::id(),
        ]);

        return redirect()->route('admin.mass-leaves.checkout', $mass_leaf->id)
            ->with('success', "ACC Kepulangan berhasil diproses untuk {$successCount} santri! ({$blockedCount} santri tertahan karena tanggungan pelanggaran).");
    }

    public function checkin(MassLeave $mass_leaf)
    {
        if (!$mass_leaf->is_active) {
            return redirect()->route('admin.mass-leaves.index')->with('error', 'Event ini sudah tidak aktif.');
        }

        $allCheckedOut = MassLeaveStudent::with('student.rayon', 'student.room')
            ->where('mass_leave_id', $mass_leaf->id)
            ->orderByDesc('checked_out_at')
            ->get();

        $totalCheckedOut = $allCheckedOut->count();
        $returnedCount = $allCheckedOut->whereNotNull('actual_return_date')->count();
        $notReturnedCount = $allCheckedOut->whereNull('actual_return_date')->count();

        // Daftar santri yang sudah pulang dan belum kembali (diurutkan yang belum kembali duluan)
        $notReturnedStudents = $allCheckedOut->sortBy(function($item) {
            return $item->actual_return_date ? 1 : 0;
        })->values();

        $students = $allCheckedOut->map(function ($item) {
            return $item->student;
        })->filter()->sortBy('name')->values();

        return view('licensing.mass-leaves.checkin', compact(
            'mass_leaf', 'students',
            'totalCheckedOut', 'returnedCount', 'notReturnedCount', 'notReturnedStudents'
        ));
    }

    public function processCheckin(Request $request, MassLeave $mass_leaf)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $studentId = $request->student_id;
        $student = Student::find($studentId);

        $record = MassLeaveStudent::where('mass_leave_id', $mass_leaf->id)
            ->where('student_id', $studentId)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => "Ananda {$student->name} belum di-ACC kepulangannya pada event ini."
            ], 400);
        }

        if ($record->actual_return_date) {
            return response()->json([
                'success' => false,
                'message' => "Ananda {$student->name} sudah dicatat kembali ke pondok sebelumnya pada " . $record->actual_return_date->format('d M Y H:i') . " WIB."
            ], 400);
        }

        $record->update([
            'actual_return_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Kedatangan kembali untuk Ananda {$student->name} berhasil dicatat."
        ]);
    }

    public function edit(MassLeave $mass_leaf)
    {
        if (\Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($mass_leaf->start_date)->subDay()->startOfDay())) {
            return back()->with('error', 'Event liburan tidak dapat diedit karena sudah memasuki batas waktu H-1 atau sedang/telah berlangsung.');
        }

        $academicYears = \App\Models\Master\AcademicYear::all();
        return view('licensing.mass-leaves.edit', compact('mass_leaf', 'academicYears'));
    }

    public function update(Request $request, MassLeave $mass_leaf)
    {
        if (\Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($mass_leaf->start_date)->subDay()->startOfDay())) {
            return back()->with('error', 'Event liburan tidak dapat diubah karena sudah memasuki batas waktu H-1 atau sedang/telah berlangsung.');
        }

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.after_or_equal' => 'Tanggal mulai liburan tidak boleh sebelum tanggal hari ini.',
            'end_date.after_or_equal'   => 'Tanggal kembali tidak boleh lebih awal dari tanggal mulai libur.'
        ]);

        $overlap = MassLeave::where('status', '!=', 'completed')
            ->where('id', '!=', $mass_leaf->id)
            ->where('start_date', '<=', $validated['end_date'])
            ->where('end_date', '>=', $validated['start_date'])
            ->first();

        if ($overlap) {
            return back()->withInput()->withErrors([
                'start_date' => "Tanggal libur bentrok dengan event lain yang sudah ada: {$overlap->title} (" . \Carbon\Carbon::parse($overlap->start_date)->format('d/m/Y') . " s/d " . \Carbon\Carbon::parse($overlap->end_date)->format('d/m/Y') . ")."
            ]);
        }

        $mass_leaf->update($validated);

        return redirect()->route('admin.mass-leaves.index')->with('success', 'Event liburan serentak berhasil diperbarui.');
    }

    public function destroy(MassLeave $mass_leaf)
    {
        if (\Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($mass_leaf->start_date)->subDay()->startOfDay())) {
            return back()->with('error', 'Event liburan tidak dapat dihapus karena sudah memasuki batas waktu H-1 atau sedang/telah berlangsung.');
        }

        MassLeaveStudent::where('mass_leave_id', $mass_leaf->id)->delete();
        $mass_leaf->delete();

        return redirect()->route('admin.mass-leaves.index')->with('success', 'Event liburan serentak berhasil dihapus.');
    }

    public function toggleStatus(MassLeave $mass_leaf)
    {
        MassLeave::closeExpiredEvents();
        $mass_leaf->refresh();

        if ($mass_leaf->status === 'completed') {
            return back()->with('error', 'Event ini sudah selesai dan statusnya tidak dapat diubah lagi.');
        }

        if ($mass_leaf->status === 'active') {
            if (\Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($mass_leaf->start_date)->subDay()->startOfDay())) {
                return back()->with('error', 'Event liburan tidak dapat dinonaktifkan karena sudah memasuki batas waktu H-1 atau sedang berlangsung. Gunakan opsi Akhiri Event jika sudah sampai Hari H jam 18:00.');
            }

            $mass_leaf->update(['status' => 'inactive']);
            return back()->with('success', "Status event {$mass_leaf->title} berhasil dinonaktifkan.");
        } else {
            $ongoingEvent = MassLeave::all()->first(fn($l) => $l->isOngoing() && $l->id !== $mass_leaf->id);
            if ($ongoingEvent) {
                return back()->with('error', "Tidak dapat mengaktifkan event ini karena saat ini sedang ada event liburan lain yang berlangsung ({$ongoingEvent->title}). Selesaikan terlebih dahulu event yang sedang berjalan.");
            }

            if (\Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($mass_leaf->end_date)->startOfDay())) {
                return back()->with('error', 'Event ini tidak dapat diaktifkan karena masa berlakunya sudah berakhir.');
            }

            MassLeave::where('status', 'active')->where('id', '!=', $mass_leaf->id)->update(['status' => 'inactive']);
            $mass_leaf->update(['status' => 'active']);

            return back()->with('success', "Event {$mass_leaf->title} berhasil diaktifkan.");
        }
    }

    public function finishEvent(MassLeave $mass_leaf)
    {
        if ($mass_leaf->status === 'completed') {
            return back()->with('error', 'Event ini sudah selesai.');
        }

        if (!$mass_leaf->canBeFinishedManually()) {
            return back()->with('error', 'Event baru dapat diakhiri secara manual pada Hari H (Tanggal Wajib Kembali) mulai pukul 18:00 WIB.');
        }

        $mass_leaf->update(['status' => 'completed']);
        return back()->with('success', "Event {$mass_leaf->title} berhasil diakhiri / dinyatakan selesai.");
    }
}

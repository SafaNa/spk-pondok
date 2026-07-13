<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use App\Models\Licensing\LeaveCategory;
use App\Models\Licensing\StudentLicense;
use App\Models\Master\AcademicYear;
use App\Models\Master\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $academicYears  = AcademicYear::orderBy('created_at', 'desc')->get();
        $activeYear     = AcademicYear::where('status', 'active')->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear?->id);

        // KPI counts (year-scoped, before user filters)
        $kpiBase       = StudentLicense::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId));
        $totalAll      = (clone $kpiBase)->count();
        $totalPending  = (clone $kpiBase)->where('status', 'pending')->count();
        $totalApproved = (clone $kpiBase)->where('status', 'approved')->count();
        $totalRejected = (clone $kpiBase)->where('status', 'rejected')->count();

        $query = StudentLicense::with([
                'student'      => fn($q) => $q->withCount('pendingViolations'),
                'student.room',
                'student.rayon',
                'academicYear',
            ])
            ->when($selectedYearId,            fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('type'),   fn($q) => $q->where('type',   $request->type))
            ->when($request->filled('start_date'), fn($q) => $q->whereDate('start_date', '>=', $request->start_date))
            ->when($request->filled('end_date'),   fn($q) => $q->whereDate('end_date',   '<=', $request->end_date))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->whereHas('student', fn($sq) => $sq->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"));
            });

        $recentLicenses = $query->latest()->paginate(10)->withQueryString();

        return view('licensing.index', compact(
            'recentLicenses', 'academicYears', 'selectedYearId',
            'totalAll', 'totalPending', 'totalApproved', 'totalRejected'
        ));
    }

    // Individual License Form
    public function create()
    {
        $students   = Student::orderBy('name')->limit(50)->get();
        $categories = LeaveCategory::orderBy('order')->get();
        return view('licensing.create', compact('students', 'categories'));
    }

    // Store Individual License
    public function storeIndividual(Request $request)
    {
        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.');
        }

        $validated = $request->validate([
            'student_id'         => 'required|exists:students,id',
            'leave_reason_id'    => 'nullable|exists:leave_reasons,id',
            'description'        => 'nullable|string|max:500',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
            'is_emergency'       => 'boolean',
        ]);

        $leaveCategoryId = null;
        if (!empty($validated['leave_reason_id'])) {
            $reason = \App\Models\Licensing\LeaveReason::find($validated['leave_reason_id']);
            $leaveCategoryId = $reason?->leave_category_id;
        }

        StudentLicense::create([
            'student_id'        => $validated['student_id'],
            'academic_year_id'  => $activeYear->id,
            'leave_category_id' => $leaveCategoryId,
            'leave_reason_id'   => $validated['leave_reason_id'] ?? null,
            'submitted_at'      => now(),
            'type' => 'individual',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending',
            'is_emergency' => $request->has('is_emergency'),
            'description' => $validated['description'] ?? null,
            'source' => 'admin',
            'creator_id' => auth()->id(),
            'creator_type' => \App\Models\User::class,
        ]);

        // WhatsApp Notification Link
        $waRedirectUrl = null;
        try {
            $student = Student::find($validated['student_id']);
            if ($student && $student->notification_phone) {
                $service = new \App\Services\WhatsAppService();
                $startDate = Carbon::parse($validated['start_date'])->format('d-m-Y');
                $endDate = Carbon::parse($validated['end_date'])->format('d-m-Y');
                $message = "IZIN PULANG: \n" .
                    "Ananda {$student->name} telah diberikan izin pulang/keluar. \n" .
                    "Tanggal: {$startDate} s.d {$endDate}. \n" .
                    "Keterangan: {$validated['description']}. \n" .
                    "Mohon pengawasannya. Terima kasih.";
                $waRedirectUrl = $service->getRedirectUrl($student->notification_phone, $message);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate WA Link License: " . $e->getMessage());
        }

        return redirect()->route('admin.licenses.index')
            ->with('success', 'Izin individu berhasil dicatat.')
            ->with('wa_url', $waRedirectUrl);
    }

    // Detail Page
    public function show(StudentLicense $license)
    {
        $license->load([
            'student'      => fn($q) => $q->withCount('pendingViolations'),
            'student.room',
            'student.rayon',
            'academicYear',
            'leaveCategory',
            'leaveReason',
        ]);

        $approvedCount = StudentLicense::where('student_id', $license->student_id)
            ->where('academic_year_id', $license->academic_year_id)
            ->where('status', 'approved')
            ->count();

        $maxLeaves         = $license->academicYear?->max_leaves;
        $canSkip           = $license->leaveReason?->can_skip_validation ?? false;
        $violationCount    = $license->student->pending_violations_count;

        $memorization = \App\Models\Licensing\StudentMemorization::where('student_id', $license->student_id)
            ->where('academic_year_id', $license->academic_year_id)
            ->where('is_used', false)
            ->first();

        $hafalanPass = $memorization && $memorization->status === 'completed';
        $hafalanDetail = 'Belum ada data hafalan';
        if ($memorization) {
            $hafalanDetail = $memorization->status === 'completed' ? 'Selesai' : 'Belum selesai';
        }

        $validation = [
            'poin' => [
                'label'   => 'Poin Kepulangan',
                'pass'    => $maxLeaves === null || $approvedCount < $maxLeaves,
                'detail'  => $maxLeaves ? "Kuota: {$approvedCount}/{$maxLeaves}" : 'Tidak ada batas kuota',
                'pending' => false,
            ],
            'pelanggaran' => [
                'label'   => 'Status Pelanggaran',
                'pass'    => $violationCount === 0,
                'detail'  => $violationCount > 0 ? "{$violationCount} pelanggaran aktif" : 'Bersih',
                'pending' => false,
            ],
            'alasan' => [
                'label'   => 'Alasan Kepulangan',
                'pass'    => $license->leave_reason_id !== null,
                'detail'  => $license->leaveReason?->reason ?? 'Belum dipilih',
                'pending' => false,
            ],
            'hafalan' => [
                'label'   => 'Setoran Hafalan',
                'pass'    => $hafalanPass,
                'detail'  => $hafalanDetail,
                'pending' => false,
            ],
        ];

        $allPass = collect($validation)->filter(fn($v) => !$v['pending'])->every(fn($v) => $v['pass']);

        return view('licensing.show', compact(
            'license', 'approvedCount', 'maxLeaves',
            'validation', 'allPass', 'canSkip'
        ));
    }

    // Edit Form
    public function edit(StudentLicense $license)
    {
        $students   = Student::orderBy('name')->limit(100)->get();
        $categories = \App\Models\Licensing\LeaveCategory::orderBy('order')->get();
        return view('licensing.edit', compact('license', 'students', 'categories'));
    }

    // Update License
    public function update(Request $request, StudentLicense $license)
    {
        $validated = $request->validate([
            'student_id'      => 'required|exists:students,id',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'leave_reason_id' => 'nullable|exists:leave_reasons,id',
            'description'     => 'nullable|string|max:500',
            'is_emergency'    => 'boolean',
        ]);

        $leaveCategoryId = null;
        if (!empty($validated['leave_reason_id'])) {
            $leaveCategoryId = \App\Models\Licensing\LeaveReason::find($validated['leave_reason_id'])?->leave_category_id;
        }

        $license->update([
            'student_id'        => $validated['student_id'],
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'leave_category_id' => $leaveCategoryId,
            'leave_reason_id'   => $validated['leave_reason_id'] ?? null,
            'is_emergency'      => $request->has('is_emergency'),
            'description'       => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.licenses.index')->with('success', 'Data izin berhasil diperbarui.');
    }

    private function waUrl(StudentLicense $license, string $message): ?string
    {
        $student = $license->student;
        $phone   = $student->guardians()->whereNotNull('phone')->value('phone')
                ?? $student->phone
                ?? null;
        if (!$phone) return null;
        return (new \App\Services\WhatsAppService())->getRedirectUrl($phone, $message);
    }

    public function approve(Request $request, StudentLicense $license)
    {
        $data = ['status' => 'approved', 'approved_at' => now()];
        if ($request->has('override_validation') && $request->override_validation == '1') {
            $data['is_emergency'] = true;
            $data['notes'] = 'Diloloskan paksa: ' . $request->override_reason;
        }
        $license->update($data);
        
        $memorization = \App\Models\Licensing\StudentMemorization::where('student_id', $license->student_id)
            ->where('academic_year_id', $license->academic_year_id)
            ->where('status', 'completed')
            ->where('is_used', false)
            ->first();
            
        if ($memorization) {
            $memorization->update(['is_used' => true]);
        }

        $license->load('student.guardians');
        $start = $license->start_date->format('d-m-Y');
        $end   = $license->end_date->format('d-m-Y');
        $waUrl = $this->waUrl($license,
            "IZIN PULANG DISETUJUI\n" .
            "Ananda {$license->student->name} telah mendapat izin pulang.\n" .
            "Tanggal: {$start} s.d {$end}.\n" .
            "Harap jaga dan awasi kepulangan ananda. Terima kasih."
        );

        return back()->with('success', 'Izin berhasil disetujui.')->with('wa_url', $waUrl);
    }

    public function forceApprove(StudentLicense $license)
    {
        $license->update(['status' => 'approved', 'is_emergency' => true, 'approved_at' => now()]);

        $memorization = \App\Models\Licensing\StudentMemorization::where('student_id', $license->student_id)
            ->where('academic_year_id', $license->academic_year_id)
            ->where('status', 'completed')
            ->where('is_used', false)
            ->first();

        if ($memorization) {
            $memorization->update(['is_used' => true]);
        }

        $license->load('student.guardians');
        $start = $license->start_date->format('d-m-Y');
        $end   = $license->end_date->format('d-m-Y');
        $waUrl = $this->waUrl($license,
            "IZIN PULANG DISETUJUI (DARURAT)\n" .
            "Ananda {$license->student->name} mendapat izin pulang atas pertimbangan darurat.\n" .
            "Tanggal: {$start} s.d {$end}.\n" .
            "Harap jaga dan awasi kepulangan ananda. Terima kasih."
        );

        return back()->with('success', 'Izin disetujui sebagai kasus darurat.')->with('wa_url', $waUrl);
    }

    public function reject(StudentLicense $license)
    {
        $license->update(['status' => 'rejected', 'rejected_at' => now()]);

        $license->load('student.guardians');
        $waUrl = $this->waUrl($license,
            "IZIN PULANG DITOLAK\n" .
            "Pengajuan izin pulang Ananda {$license->student->name} tidak dapat disetujui saat ini.\n" .
            "Silakan hubungi pihak pesantren untuk informasi lebih lanjut. Terima kasih."
        );

        return back()->with('success', 'Izin berhasil ditolak.')->with('wa_url', $waUrl);
    }

    public function destroy(StudentLicense $license)
    {
        $license->delete();
        return redirect()->route('admin.licenses.index')->with('success', 'Data izin berhasil dihapus.');
    }

    public function recordReturn(Request $request, StudentLicense $license)
    {
        $request->validate([
            'actual_return_date' => 'required|date',
            'return_notes'       => 'nullable|string',
        ]);

        $license->update([
            'actual_return_date' => $request->actual_return_date,
            'return_notes'       => $request->return_notes,
        ]);

        $status = $license->is_late
            ? 'Terlambat ' . $license->late_days . ' hari'
            : 'Tepat waktu';

        $license->load('student.guardians');
        $returnDate = \Carbon\Carbon::parse($request->actual_return_date)->format('d-m-Y');
        $waUrl = $this->waUrl($license,
            "SANTRI KEMBALI KE PESANTREN\n" .
            "Ananda {$license->student->name} telah kembali ke pesantren.\n" .
            "Tanggal kembali: {$returnDate}. Status: {$status}.\n" .
            "Terima kasih atas kerjasamanya."
        );

        return back()
            ->with('success', "Kepulangan santri berhasil dicatat. Status: {$status}.")
            ->with('wa_url', $waUrl);
    }

    public function active(Request $request)
    {
        $search = $request->input('search');
        
        $query = StudentLicense::with(['student.rayon', 'student.room', 'leaveCategory'])
            ->where('status', 'approved')
            ->whereNull('actual_return_date');
            
        if ($search) {
            $query->whereHas('student', function($q) use ($search) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(nis) like ?', ['%' . strtolower($search) . '%']);
            });
        }
        
        $licenses = $query->orderBy('start_date', 'desc')->paginate(15);
        
        return view('licensing.active', compact('licenses', 'search'));
    }

    public function activeShow(StudentLicense $license)
    {
        $license->load([
            'student.rayon',
            'student.room',
            'student.guardians',
            'leaveCategory',
            'leaveReason',
            'academicYear',
        ]);

        return view('licensing.active-show', compact('license'));
    }
}

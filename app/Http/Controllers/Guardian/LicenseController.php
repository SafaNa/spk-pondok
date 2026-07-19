<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Licensing\LeaveCategory;
use App\Models\Licensing\LeaveReason;
use App\Models\Licensing\LicenseExtension;
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

        $licenses = StudentLicense::with(['student', 'extensions'])
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
    public function show(StudentLicense $license)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        if (!$studentIds->contains($license->student_id)) {
            abort(403, 'Akses ditolak.');
        }

        $license->load(['student', 'leaveCategory', 'leaveReason', 'extensions']);
        return view('guardian.licenses.show', compact('guardian', 'license'));
    }

    public function edit(StudentLicense $license)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        if (!$studentIds->contains($license->student_id)) {
            abort(403, 'Akses ditolak.');
        }

        if ($license->status !== 'pending') {
            return redirect()->route('guardian.licenses.index')->with('error', 'Izin yang sudah diproses tidak dapat diubah.');
        }

        $students   = $guardian->students()->get();
        $activeYear = AcademicYear::where('status', 'active')->first();
        $categories = LeaveCategory::orderBy('order')->get();

        return view('guardian.licenses.edit', compact('guardian', 'license', 'students', 'activeYear', 'categories'));
    }

    public function update(Request $request, StudentLicense $license)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        if (!$studentIds->contains($license->student_id)) {
            abort(403, 'Akses ditolak.');
        }

        if ($license->status !== 'pending') {
            return redirect()->route('guardian.licenses.index')->with('error', 'Izin yang sudah diproses tidak dapat diubah.');
        }

        $request->validate([
            'leave_reason_id' => 'required|exists:leave_reasons,id',
            'description'     => 'nullable|string|max:500',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'is_emergency'    => 'boolean',
            'attachment'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $reason = LeaveReason::find($request->leave_reason_id);
        
        $data = [
            'leave_category_id' => $reason?->leave_category_id,
            'leave_reason_id'   => $request->leave_reason_id,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'description'       => $request->description,
            'is_emergency'      => $request->has('is_emergency'),
        ];

        if ($request->hasFile('attachment')) {
            if ($license->attachment && Storage::disk('public')->exists($license->attachment)) {
                Storage::disk('public')->delete($license->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('license-attachments', 'public');
        }

        $license->update($data);

        return redirect()->route('guardian.licenses.index')->with('success', 'Pengajuan izin berhasil diperbarui.');
    }

    public function destroy(StudentLicense $license)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        if (!$studentIds->contains($license->student_id)) {
            abort(403, 'Akses ditolak.');
        }

        if ($license->status !== 'pending') {
            return redirect()->route('guardian.licenses.index')->with('error', 'Izin yang sudah diproses tidak dapat dibatalkan.');
        }

        if ($license->attachment && Storage::disk('public')->exists($license->attachment)) {
            Storage::disk('public')->delete($license->attachment);
        }

        $license->delete();

        return redirect()->route('guardian.licenses.index')->with('success', 'Pengajuan izin berhasil dibatalkan dan dihapus.');
    }
    /**
     * GET: Halaman form pengajuan perpanjangan izin.
     */
    public function requestExtension(StudentLicense $license)
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        // Pastikan izin ini milik santri yang ada di akun wali
        if (!$studentIds->contains($license->student_id)) {
            abort(403, 'Anda tidak memiliki akses ke izin ini.');
        }

        // Hanya izin yang sudah approved dan belum kembali yang bisa diperpanjang
        if ($license->status !== 'approved' || $license->actual_return_date) {
            return redirect()->route('guardian.licenses.index')
                ->with('error', 'Izin ini tidak dapat diperpanjang.');
        }

        // Load relasi district untuk cek kecamatan
        $license->load(['student.district', 'extensions']);

        $requiresInPerson = $license->requiresInPersonExtension();
        $activeExtension  = $license->active_extension;
        $extensions       = $license->extensions;

        return view('guardian.licenses.extend', compact(
            'license', 'guardian', 'requiresInPerson', 'activeExtension', 'extensions'
        ));
    }

    /**
     * POST: Simpan pengajuan perpanjangan izin.
     */
    public function storeExtension(Request $request, StudentLicense $license)
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('students.id');

        if (!$studentIds->contains($license->student_id)) {
            abort(403);
        }

        if ($license->status !== 'approved' || $license->actual_return_date) {
            return back()->with('error', 'Izin ini tidak dapat diperpanjang.');
        }

        // Cek tidak ada extension pending
        $license->load(['extensions', 'student.district']);
        if ($license->active_extension) {
            return back()->with('error', 'Sudah ada pengajuan perpanjangan yang sedang menunggu persetujuan.');
        }

        // Cek district wajib datang
        if ($license->requiresInPersonExtension()) {
            return back()->with('error', 'Perpanjangan harus diajukan langsung ke pondok.');
        }

        // Batas maksimal tanggal baru: end_date + 3 hari
        $maxNewEndDate = $license->end_date->copy()->addDays(3)->format('Y-m-d');

        $request->validate([
            'requested_new_end_date' => [
                'required', 'date',
                'after:'  . $license->end_date->format('Y-m-d'),
                'before_or_equal:' . $maxNewEndDate,
            ],
            'notes'      => 'nullable|string|max:500',
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'requested_new_end_date.after'           => 'Tanggal baru harus setelah tanggal kembali saat ini.',
            'requested_new_end_date.before_or_equal' => 'Perpanjangan maksimal 3 hari dari tanggal kembali saat ini (' . $maxNewEndDate . ').',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('extension-attachments', 'public');
        }

        LicenseExtension::create([
            'student_license_id'     => $license->id,
            'requested_new_end_date' => $request->requested_new_end_date,
            'status'                 => 'pending',
            'source'                 => 'guardian',
            'attachment'             => $attachmentPath,
            'notes'                 => $request->notes,
            'requested_at'           => now(),
            'created_by_type'        => Guardian::class,
            'created_by_id'          => $guardian->id,
        ]);

        return redirect()->route('guardian.licenses.index')
            ->with('success', 'Pengajuan perpanjangan berhasil dikirim. Santri boleh tetap di rumah sambil menunggu persetujuan.');
    }
}

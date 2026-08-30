<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\SppPayment;
use App\Models\Master\Student;
use App\Models\Master\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SppPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all academic years for filter dropdown
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();

        // Determine selected year: request > active > latest
        $selectedYearId = $request->input('academic_year_id');

        if (!$selectedYearId) {
            $selectedYearId = $activeYear ? $activeYear->id : ($academicYears->first() ? $academicYears->first()->id : null);
        }

        // Base query
        $query = SppPayment::with(['student', 'academicYear', 'user'])
            ->latest('payment_date');

        // Apply Filter
        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }

        $payments = $query->paginate(10)->withQueryString();

        // Context data for view
        // If we want the Tariff Badge to show the tariff of the SELECTED year being viewed:
        $currentContextYear = $academicYears->find($selectedYearId);

        return view('finance.spp.index', compact('payments', 'academicYears', 'selectedYearId', 'currentContextYear', 'activeYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeYear = AcademicYear::where('status', 'active')->first()
            ?? AcademicYear::latest()->first();

        $preselectedStudent = old('student_id')
            ? Student::with('room', 'rayon')->find(old('student_id'))
            : null;

        return view('finance.spp.create', compact('activeYear', 'preselectedStudent'));
    }

    public function searchStudents(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $escaped   = addcslashes($q, '%_\\');
        $includeId = $request->input('include_id');

        $students = Student::with('room', 'rayon')
            ->where(function ($query) use ($includeId) {
                $query->where('status', 'active');
                if ($includeId) {
                    $query->orWhere('id', $includeId);
                }
            })
            ->where(function ($query) use ($escaped) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($escaped) . '%'])
                      ->orWhereRaw('LOWER(nis) LIKE ?',  ['%' . strtolower($escaped) . '%']);
            })
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn($s) => [
                'id'   => $s->id,
                'text' => $s->name,
                'info' => ($s->nis ?? '-') . ' · ' . ($s->rayon?->name ?? '') . ' - ' . ($s->room?->name ?? 'Belum ada kamar'),
            ]);

        return response()->json(['results' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'stage' => 'required|in:1,2,full',
            'amount' => 'required|integer|min:0',
            'payment_date' => 'required|date',
            // 'deadline' => 'nullable|date', // Removed manual deadline
            'status' => 'required|in:paid,pending',
            'note' => 'nullable|string',
            'is_late_fee_waived' => 'nullable|boolean',
        ]);

        $academicYear = \App\Models\Master\AcademicYear::findOrFail($request->academic_year_id);

        // Determine Deadline based on Stage
        $deadline = null;
        if ($request->stage == '1' || $request->stage == 'full') {
            // Full payment follows Stage 1 deadline (early payment logic)
            $deadline = $academicYear->stage1_deadline;
        } elseif ($request->stage == '2') {
            $deadline = $academicYear->stage2_deadline;
        }

        // Calculate Late Fee
        $lateFee = 0;
        $isWaived = $request->has('is_late_fee_waived');

        if ($deadline && $request->payment_date > $deadline->format('Y-m-d') && !$isWaived) {
            $lateFee = 500;
        }

        SppPayment::create([
            'student_id' => $request->student_id,
            'academic_year_id' => $request->academic_year_id,
            'stage' => $request->stage,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'deadline' => $deadline, // Save the deadline used for record
            'late_fee' => $lateFee,
            'is_late_fee_waived' => $isWaived,
            'status' => $request->status,
            'note' => $request->note,
            'user_id' => Auth::id(), // Record who created it
        ]);

        // WhatsApp Notification
        $waNotification = null;
        try {
            $student = Student::find($request->student_id);
            if ($student) {
                $stageText = $request->stage == 'full' ? 'LUNAS (Full)' : "Tahap {$request->stage}";
                $statusText = $request->status === 'paid' ? 'telah diterima dan lunas' : 'telah dicatat namun belum lunas (menunggu konfirmasi)';
                $amountFormatted = number_format($request->amount, 0, ',', '.');
                $message = "Assalamualaikum Wr. Wb. Bapak/Ibu,\n\n" .
                    "Informasi dari *Pengurus Pesantren (Bagian Keuangan)*:\n\n" .
                    "*PEMBAYARAN SPP*\n" .
                    "Telah dilakukan pembayaran SPP {$stageText} untuk santri:\n" .
                    "👤 Nama: *{$student->name}*\n" .
                    "🔖 {$student->identifier_label}: {$student->nis}\n" .
                    "🏠 Kamar: " . ($student->room?->name ?? '-') . " (" . ($student->rayon?->name ?? '-') . ")\n\n" .
                    "Nominal: *Rp {$amountFormatted}*\n" .
                    "Status: {$statusText}.\n\n" .
                    "Terima kasih atas kerja sama Bapak/Ibu.\n\n" .
                    "---\n" .
                    "🤖 _Pesan otomatis sistem Pesantren._\n" .
                    "_Mohon balas *\"BAIK\"* atau *\"OK\"* sebagai tanda konfirmasi bahwa pesan ini telah diterima._";
                $phone = $student->guardians()->whereNotNull('phone')->value('phone')
                       ?? $student->notification_phone
                       ?? $student->phone
                       ?? null;
                if ($phone) {
                    $waNotification = [
                        'phone' => $phone,
                        'message' => $message
                    ];
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to prepare WA SPP: " . $e->getMessage());
        }

        return redirect()->route('admin.spp-payments.index')
            ->with('success', 'Pembayaran SPP berhasil ditambahkan')
            ->with('wa_notification', $waNotification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SppPayment $sppPayment)
    {
        $sppPayment->load('student.room', 'student.rayon');
        $academicYears = AcademicYear::all();
        return view('finance.spp.edit', compact('sppPayment', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SppPayment $sppPayment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'stage' => 'required|in:1,2,full',
            'amount' => 'required|integer|min:0',
            'payment_date' => 'required|date',
            // 'deadline' => 'nullable|date', // Removed manual deadline
            'status' => 'required|in:paid,pending',
            'note' => 'nullable|string',
            'is_late_fee_waived' => 'nullable|boolean',
        ]);

        $academicYear = \App\Models\Master\AcademicYear::findOrFail($request->academic_year_id);

        // Determine Deadline based on Stage
        $deadline = null;
        if ($request->stage == '1' || $request->stage == 'full') {
            $deadline = $academicYear->stage1_deadline;
        } elseif ($request->stage == '2') {
            $deadline = $academicYear->stage2_deadline;
        }

        // Calculate Late Fee
        $lateFee = 0;
        $isWaived = $request->has('is_late_fee_waived');

        if ($deadline && $request->payment_date > $deadline->format('Y-m-d') && !$isWaived) {
            $lateFee = 500;
        }

        $sppPayment->update([
            'student_id' => $request->student_id,
            'academic_year_id' => $request->academic_year_id,
            'stage' => $request->stage,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'deadline' => $deadline, // Save the deadline used for record
            'late_fee' => $lateFee,
            'is_late_fee_waived' => $isWaived,
            'status' => $request->status,
            'note' => $request->note,
            // user_id typically not updated on edit, or maybe strictly for creation logging
        ]);

        // WhatsApp Notification
        $waNotification = null;
        try {
            $student = Student::find($request->student_id);
            if ($student) {
                $stageText = $request->stage == 'full' ? 'LUNAS (Full)' : "Tahap {$request->stage}";
                $statusText = $request->status === 'paid' ? 'Lunas' : 'Belum Lunas';
                $amountFormatted = number_format($request->amount, 0, ',', '.');
                $message = "Assalamualaikum Wr. Wb. Bapak/Ibu,\n\n" .
                    "Informasi dari *Pengurus Pesantren (Bagian Keuangan)*:\n\n" .
                    "*PEMBARUAN DATA SPP*\n" .
                    "Data pembayaran SPP {$stageText} untuk santri:\n" .
                    "👤 Nama: *{$student->name}*\n" .
                    "🔖 {$student->identifier_label}: {$student->nis}\n" .
                    "🏠 Kamar: " . ($student->room?->name ?? '-') . " (" . ($student->rayon?->name ?? '-') . ")\n\n" .
                    "Telah diperbarui. Nominal: *Rp {$amountFormatted}*\n" .
                    "📋 Status saat ini: *{$statusText}*\n\n" .
                    "Terima kasih atas kerja sama Bapak/Ibu.\n\n" .
                    "---\n" .
                    "🤖 _Pesan otomatis sistem Pesantren._\n" .
                    "_Mohon balas *\"BAIK\"* atau *\"OK\"* sebagai tanda konfirmasi bahwa pesan ini telah diterima._";
                $phone = $student->guardians()->whereNotNull('phone')->value('phone')
                       ?? $student->notification_phone
                       ?? $student->phone
                       ?? null;
                if ($phone) {
                    $waNotification = [
                        'phone' => $phone,
                        'message' => $message
                    ];
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to prepare WA SPP Update: " . $e->getMessage());
        }

        return redirect()->route('admin.spp-payments.index')
            ->with('success', 'Pembayaran SPP berhasil diperbarui')
            ->with('wa_notification', $waNotification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SppPayment $sppPayment)
    {
        $sppPayment->delete();

        return redirect()->route('admin.spp-payments.index')
            ->with('success', 'Pembayaran SPP berhasil dihapus');
    }
}

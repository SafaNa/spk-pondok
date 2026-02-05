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
        // Simple optimization: Get active (or all) students and academic years
        $students = Student::orderBy('name')->get();
        // Get the single active academic year for the hidden field
        $activeYear = AcademicYear::where('status', 'active')->first();

        // Fallback if no active year (though system should have one)
        if (!$activeYear) {
            $activeYear = AcademicYear::latest()->first();
        }

        return view('finance.spp.create', compact('students', 'activeYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'amount' => 'required|integer|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending',
            'note' => 'nullable|string',
        ]);

        SppPayment::create([
            'student_id' => $request->student_id,
            'academic_year_id' => $request->academic_year_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
            'note' => $request->note,
            'user_id' => Auth::id(), // Record who created it
        ]);

        // WhatsApp Notification Link
        $waRedirectUrl = null;
        try {
            $student = Student::find($request->student_id);
            if ($student && $student->notification_phone) {
                $service = new \App\Services\WhatsAppService();
                $message = "Pembayaran SPP atas nama {$student->name} sebesar Rp " . number_format($request->amount, 0, ',', '.') . " telah diterima (Status: {$request->status}). Terima kasih.";
                $waRedirectUrl = $service->getRedirectUrl($student->notification_phone, $message);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate WA Link SPP: " . $e->getMessage());
        }

        return redirect()->route('spp-payments.index')
            ->with('success', 'Pembayaran SPP berhasil ditambahkan')
            ->with('wa_url', $waRedirectUrl);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SppPayment $sppPayment)
    {
        $students = Student::orderBy('name')->get();
        $academicYears = AcademicYear::all();
        return view('finance.spp.edit', compact('sppPayment', 'students', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SppPayment $sppPayment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'amount' => 'required|integer|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending',
            'note' => 'nullable|string',
        ]);

        $sppPayment->update([
            'student_id' => $request->student_id,
            'academic_year_id' => $request->academic_year_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
            'note' => $request->note,
            // user_id typically not updated on edit, or maybe strictly for creation logging
        ]);

        // Send WhatsApp Notification (Update)
        try {
            $student = Student::find($request->student_id);
            if ($student && $student->notification_phone) {
                $service = new \App\Services\WhatsAppService();
                $message = "Update Pembayaran SPP atas nama {$student->name} sebesar Rp " . number_format($request->amount, 0, ',', '.') . ". Status saat ini: {$request->status}.";
                $service->sendMessage($student->notification_phone, $message);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send WA SPP Update: " . $e->getMessage());
        }

        return redirect()->route('spp-payments.index')
            ->with('success', 'Pembayaran SPP berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SppPayment $sppPayment)
    {
        $sppPayment->delete();

        return redirect()->route('spp-payments.index')
            ->with('success', 'Pembayaran SPP berhasil dihapus');
    }
}

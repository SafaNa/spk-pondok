<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
// use App\Models\Licensing\LicensingEvent; // Removed
use App\Models\Licensing\StudentLicense;
use App\Models\Master\MemorizationType;
use App\Models\Master\Student;
use App\Models\Master\Rayon;
use App\Models\Master\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function index()
    {
        // Removed Active Events logic
        $recentLicenses = StudentLicense::with('student')->latest()->take(10)->get();
        return view('licensing.index', compact('recentLicenses'));
    }



    // Individual License Form
    public function create()
    {
        $students = Student::orderBy('name')->limit(50)->get(); // For dropdown placeholder
        return view('licensing.create', compact('students'));
    }

    // Store Individual License
    public function storeIndividual(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'memorization_check' => 'boolean',
        ]);

        StudentLicense::create([
            'student_id' => $validated['student_id'],
            'type' => 'individual',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'approved',
            'memorization_check' => $request->has('memorization_check'),
            'description' => $validated['description'],
        ]);

        // WhatsApp Notification Link
        $waRedirectUrl = null;
        try {
            $student = Student::find($validated['student_id']);
            if ($student && $student->notification_phone) {
                $service = new \App\Services\WhatsAppService();
                $startDate = \Carbon\Carbon::parse($validated['start_date'])->format('d-m-Y');
                $endDate = \Carbon\Carbon::parse($validated['end_date'])->format('d-m-Y');
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

        return redirect()->route('licenses.index')
            ->with('success', 'Izin individu berhasil dicatat.')
            ->with('wa_url', $waRedirectUrl);
    }

    // Edit Form
    public function edit(StudentLicense $license)
    {
        $students = Student::orderBy('name')->limit(100)->get();
        return view('licensing.edit', compact('license', 'students'));
    }

    // Update License
    public function update(Request $request, StudentLicense $license)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'memorization_check' => 'boolean',
        ]);

        $license->update([
            'student_id' => $validated['student_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            // 'status' => 'approved', // Status not updated here normally
            'memorization_check' => $request->has('memorization_check'),
            'description' => $validated['description'],
        ]);

        return redirect()->route('licenses.index')->with('success', 'Data izin berhasil diperbarui.');
    }

    // Removed calculateTargets as it's no longer used/requested
}

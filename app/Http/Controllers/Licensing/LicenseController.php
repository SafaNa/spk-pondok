<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use App\Models\Licensing\StudentLicense;
use App\Models\Master\AcademicYear;
use App\Models\Master\MemorizationType;
use App\Models\Master\Student;
use App\Models\Master\Rayon;
use App\Models\Master\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        $activeYear = AcademicYear::where('status', 'active')->first();

        // Filter by selected academic year or active year
        $selectedYearId = $request->get('academic_year_id', $activeYear?->id);

        $recentLicenses = StudentLicense::with([
            'student' => function ($query) {
                $query->withCount('pendingViolations');
            },
            'student.room',
            'academicYear'
        ])
            ->when($selectedYearId, function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId);
            })
            ->latest()->paginate(10);

        return view('licensing.index', compact('recentLicenses', 'academicYears', 'selectedYearId'));
    }

    // Individual License Form
    public function create()
    {
        $students = Student::orderBy('name')->limit(50)->get();
        $activeYear = AcademicYear::where('status', 'active')->first();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        return view('licensing.create', compact('students', 'activeYear', 'academicYears'));
    }

    // Store Individual License
    public function storeIndividual(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'memorization_check' => 'boolean',
        ]);

        StudentLicense::create([
            'student_id' => $validated['student_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'type' => 'individual',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending',
            'memorization_check' => $request->has('memorization_check'),
            'description' => $validated['description'],
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

        return redirect()->route('licenses.index')
            ->with('success', 'Izin individu berhasil dicatat.')
            ->with('wa_url', $waRedirectUrl);
    }

    // Detail Page
    public function show(StudentLicense $license)
    {
        $license->load([
            'student' => function ($query) {
                $query->withCount('pendingViolations');
            },
            'student.room',
            'student.rayon',
            'academicYear'
        ]);
        return view('licensing.show', compact('license'));
    }

    // Edit Form
    public function edit(StudentLicense $license)
    {
        $students = Student::orderBy('name')->limit(100)->get();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        return view('licensing.edit', compact('license', 'students', 'academicYears'));
    }

    // Update License
    public function update(Request $request, StudentLicense $license)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'memorization_check' => 'boolean',
        ]);

        $license->update([
            'student_id' => $validated['student_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'memorization_check' => $request->has('memorization_check'),
            'description' => $validated['description'],
        ]);

        return redirect()->route('licenses.index')->with('success', 'Data izin berhasil diperbarui.');
    }

    public function approve($id)
    {
        $license = StudentLicense::findOrFail($id);
        $license->status = 'approved';
        $license->save();

        return back()->with('success', 'Izin berhasil disetujui.');
    }

    public function reject($id)
    {
        $license = StudentLicense::findOrFail($id);
        $license->status = 'rejected';
        $license->save();

        return back()->with('success', 'Izin berhasil ditolak.');
    }
}

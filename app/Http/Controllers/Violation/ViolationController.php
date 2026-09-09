<?php

namespace App\Http\Controllers\Violation;

use App\Http\Controllers\Controller;
use App\Models\Violation\ViolationRecord;
use App\Models\Violation\ViolationType;
use App\Models\Violation\ViolationCategory;
use App\Models\Master\Student;
use App\Models\Master\AcademicYear;
use App\Models\Master\Department;
use App\Models\Master\Rayon;
use App\Models\Master\Room;
use App\Exports\ViolationExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ViolationController extends Controller
{
    private function buildQuery(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = ViolationRecord::with([
            'student.room',
            'violationType.category',
            'violationType.department',
            'creator'
        ])->orderBy('date', 'desc');

        // Batasan per departemen untuk departemen officer
        if ($user->isDepartmentOfficer()) {
            $query->whereHas('violationType', fn($q) => $q->where('department_id', $user->department_id));
        }

        // Filter tahun ajaran aktif hanya jika tidak ada filter tahun/bulan sama sekali
        if (!$request->filled('tahun') && !$request->filled('bulan')) {
            $activeYear = AcademicYear::where('status', 'active')->first();
            if ($activeYear) $query->where('academic_year_id', $activeYear->id);
        }

        // Filters
        if ($request->filled('search'))               { $query->whereHas('student', fn($q) => $q->where('name', 'like', '%' . $request->search . '%')); }
        if ($request->filled('tahun'))                { $query->whereYear('date', $request->tahun); }
        if ($request->filled('bulan'))                { $query->whereMonth('date', $request->bulan); }
        if ($request->filled('rayon_id'))              { $query->whereHas('student', fn($q) => $q->where('rayon_id', $request->rayon_id)); }
        if ($request->filled('room_id'))              { $query->whereHas('student', fn($q) => $q->where('room_id', $request->room_id)); }
        if ($request->filled('department_id'))        { $query->whereHas('violationType', fn($q) => $q->where('department_id', $request->department_id)); }
        if ($request->filled('violation_category_id')){ $query->whereHas('violationType', fn($q) => $q->where('violation_category_id', $request->violation_category_id)); }
        if ($request->filled('violation_type_id'))    { $query->where('violation_type_id', $request->violation_type_id); }
        if ($request->filled('sanction_status'))      { $query->where('sanction_status', $request->sanction_status); }

        return $query;
    }

    private function getFilterLists(): array
    {
        $earliestYear = ViolationRecord::whereNotNull('date')
            ->selectRaw('YEAR(MIN(date)) as tahun')->value('tahun');
        $tahunList = $earliestYear
            ? collect(range((int) $earliestYear, (int) now()->format('Y')))->values()
            : collect();

        return [
            'tahunList'      => $tahunList,
            'rayonList'      => Rayon::orderBy('name')->get(['id', 'name']),
            'roomList'       => Room::orderBy('name')->get(['id', 'name', 'rayon_id']),
            'departmentList' => Department::where('type', 'department')->orderBy('name')->get(['id', 'name', 'acronym']),
            'kategoriList'   => ViolationCategory::orderBy('name')->get(['id', 'name']),
            'typeList'       => ViolationType::where('is_active', true)->orderBy('name')
                                    ->get(['id', 'name', 'department_id', 'violation_category_id']),
        ];
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canManageViolations()) abort(403, 'Anda tidak memiliki akses ke halaman ini.');

        // Default tahun ini jika belum ada filter apapun
        if (!$request->hasAny(['search', 'tahun', 'bulan', 'rayon_id', 'room_id', 'department_id', 'violation_category_id', 'violation_type_id', 'sanction_status'])) {
            $request->merge(['tahun' => now()->format('Y')]);
        }

        $violations = $this->buildQuery($request)->paginate(20)->withQueryString();

        // Stats dari query yang sama tanpa paginate
        $baseQuery  = $this->buildQuery($request);
        $stats = [
            'total'     => $violations->total(),
            'pending'   => (clone $baseQuery)->where('sanction_status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('sanction_status', 'completed')->count(),
        ];

        return view('violations.index', array_merge(
            compact('violations', 'stats'),
            $this->getFilterLists()
        ));
    }

    public function exportExcel(Request $request)
    {
        if (!Auth::user()->canManageViolations()) abort(403);
        $records  = $this->buildQuery($request)->get();
        $title    = 'Rekap Pelanggaran';
        $filename = 'rekap-pelanggaran-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new ViolationExport($records, $title), $filename);
    }

    public function exportPdf(Request $request)
    {
        if (!Auth::user()->canManageViolations()) abort(403);
        $records  = $this->buildQuery($request)->get();
        $title    = 'Rekap Pelanggaran';
        $filename = 'rekap-pelanggaran-' . now()->format('Ymd-His') . '.pdf';
        $pdf = Pdf::loadView('violations.pdf', compact('records', 'title'))
            ->setPaper('a4', 'landscape')
            ->setOption(['margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 15, 'margin_right' => 15]);
        return $pdf->download($filename);
    }

    public function exportWord(Request $request)
    {
        if (!Auth::user()->canManageViolations()) abort(403);
        $records  = $this->buildQuery($request)->get();
        $title    = 'Rekap Pelanggaran';
        $filename = 'rekap-pelanggaran-' . now()->format('Ymd-His') . '.docx';

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'orientation'  => 'landscape',
            'pageSizeW'    => 16838,
            'pageSizeH'    => 11906,
            'marginTop'    => 720, 'marginBottom' => 720,
            'marginLeft'   => 720, 'marginRight'  => 720,
        ]);

        $section->addText($title, ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addText('Dicetak pada ' . now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB',
            ['size' => 9, 'color' => '94a3b8'], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Tabel — A4 landscape: 16838 - 1440 = 15398 twips
        $colWidths   = [500, 2000, 1200, 1500, 1500, 2198, 1200, 1300]; // total ~15398
        $headerStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 9];
        $headerCell  = ['bgColor' => '1E3A5F', 'valign' => 'center'];
        $headers     = ['No', 'Nama Santri', 'Kamar', 'Bidang', 'Jenis', 'Sanksi', 'Tanggal', 'Status'];

        $table = $section->addTable([
            'borderSize' => 6, 'borderColor' => 'e2e8f0', 'cellMargin' => 60,
            'width' => 15398, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
        ]);

        $table->addRow();
        foreach ($headers as $idx => $h) {
            $table->addCell($colWidths[$idx], $headerCell)->addText($h, $headerStyle);
        }

        foreach ($records as $i => $r) {
            $status = $r->sanction_status === 'completed' ? 'Selesai' : 'Belum Selesai';
            $table->addRow();
            $table->addCell($colWidths[0])->addText($i + 1);
            $table->addCell($colWidths[1])->addText($r->student->name ?? '-');
            $table->addCell($colWidths[2])->addText($r->student->room->name ?? '-');
            $table->addCell($colWidths[3])->addText($r->violationType->department->acronym ?? '-');
            $table->addCell($colWidths[4])->addText($r->violationType->name ?? '-');
            $table->addCell($colWidths[5])->addText(\Illuminate\Support\Str::limit($r->sanction ?? '-', 60));
            $table->addCell($colWidths[6])->addText($r->date?->format('d/m/Y') ?? '-');
            $table->addCell($colWidths[7])->addText($status);
        }

        $tempPath  = tempnam(sys_get_temp_dir(), 'violation_') . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    public function create()
    {
        if (!Auth::user()->canManageViolations()) abort(403, 'Anda tidak memiliki akses ke halaman ini.');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $activeAcademicYear = AcademicYear::where('status', 'active')->first();

        if (!$activeAcademicYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif. Silakan aktifkan terlebih dahulu.');
        }

        $students = collect();
        if (old('student_id')) {
            $student = Student::find(old('student_id'));
            if ($student) $students->push($student);
        }

        $query = ViolationType::where('is_active', true)->with(['category', 'department'])->orderBy('name');
        if ($user->isDepartmentOfficer()) $query->where('department_id', $user->department_id);
        $violationTypes = $query->get();

        return view('violations.create', compact('students', 'violationTypes', 'activeAcademicYear'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->canManageViolations()) abort(403, 'Anda tidak memiliki akses.');

        $validated = $request->validate([
            'student_id'        => 'required|exists:students,id',
            'violation_type_id' => 'required|exists:violation_types,id',
            'date'              => 'required|date',
            'notes'             => 'nullable|string'
        ]);

        $violationType = ViolationType::findOrFail($validated['violation_type_id']);
        $academicYear  = AcademicYear::where('status', 'active')->first();

        if (!$academicYear) return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) {
            abort(403, 'Anda tidak memiliki akses untuk mencatat pelanggaran departemen ini');
        }

        ViolationRecord::create([
            'student_id'        => $validated['student_id'],
            'violation_type_id' => $validated['violation_type_id'],
            'academic_year_id'  => $academicYear->id,
            'date'              => $validated['date'],
            'sanction'          => $violationType->default_sanction,
            'sanction_status'   => 'pending',
            'notes'             => $validated['notes'] ?? null,
            'created_by'        => Auth::id()
        ]);

        $waNotification = null;
        try {
            $student = Student::with('guardians')->find($validated['student_id']);
            if ($student) {
                $phone = $student->guardians->whereNotNull('phone')->first()?->phone ?? $student->phone ?? null;
                if ($phone) {
                    $tanggal    = \Carbon\Carbon::parse($validated['date'])->format('d-m-Y');
                    $isKewajiban = str_contains($violationType->code, '-KW-');
                    $actionText  = $isKewajiban ? "tidak mematuhi tata tertib (Kewajiban)" : "melakukan pelanggaran (Larangan)";
                    $message = "Assalamualaikum Wr. Wb. Bapak/Ibu,\n\n" .
                        "Informasi dari *Pengurus Pesantren (Bagian Kedisiplinan)*:\n\n" .
                        "*PEMBERITAHUAN PELANGGARAN*\n" .
                        "Telah tercatat informasi pelanggaran atas santri:\n" .
                        "👤 Nama: *{$student->name}*\n" .
                        "🔖 {$student->identifier_label}: {$student->nis}\n" .
                        "🏠 Kamar: " . ($student->room?->name ?? '-') . " (" . ($student->rayon?->name ?? '-') . ")\n\n" .
                        "Tercatat {$actionText}:\n" .
                        "📌 Jenis: {$violationType->name}\n" .
                        "⚖️ Sanksi: {$violationType->default_sanction}\n" .
                        "🗓 Tanggal: {$tanggal}\n\n" .
                        "Mohon kerja sama Bapak/Ibu untuk menasihati ananda. Terima kasih.\n\n" .
                        "---\n" .
                        "🤖 _Pesan otomatis sistem Pesantren._\n" .
                        "_Mohon balas *\"BAIK\"* atau *\"OK\"* sebagai tanda konfirmasi bahwa pesan ini telah diterima._";
                    $waNotification = ['phone' => $phone, 'message' => $message];
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("WhatsApp prepare failed (violation): " . $e->getMessage());
        }

        return redirect()->route('admin.violations.index')
            ->with('success', 'Pelanggaran berhasil dicatat')
            ->with('wa_notification', $waNotification);
    }

    public function show($id)
    {
        $violation = ViolationRecord::with([
            'student', 'violationType.category', 'violationType.department', 'academicYear', 'creator'
        ])->findOrFail($id);
        return view('violations.show', compact('violation'));
    }

    public function verifySanction(Request $request, $id)
    {
        $violation = ViolationRecord::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isDepartmentOfficer()) {
            $violation->load('violationType');
            if ($violation->violationType->department_id != $user->department_id) {
                abort(403, 'Anda tidak memiliki akses untuk memverifikasi sanksi departemen ini');
            }
        }
        $violation->update(['sanction_status' => 'completed', 'verified_at' => now(), 'verified_by' => Auth::id()]);
        return redirect()->back()->with('success', 'Sanksi berhasil diverifikasi sebagai selesai');
    }

    public function history($studentId)
    {
        $student    = Student::findOrFail($studentId);
        $violations = ViolationRecord::where('student_id', $studentId)
            ->with(['violationType.category', 'violationType.department', 'academicYear', 'creator', 'verifier'])
            ->orderBy('date', 'desc')->get();
        $violationsByAcademicYear = $violations->groupBy('academic_year_id');
        return view('violations.history', compact('student', 'violations', 'violationsByAcademicYear'));
    }

    public function edit($id)
    {
        if (!Auth::user()->canManageViolations()) abort(403, 'Anda tidak memiliki akses.');
        $violation = ViolationRecord::with('violationType')->findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isDepartmentOfficer() && $violation->violationType->department_id != $user->department_id) abort(403);
        if ($user->isLicensingOfficer()) abort(403);

        $activeAcademicYear = AcademicYear::where('status', 'active')->first();
        if (!$activeAcademicYear) return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');

        $students      = collect([$violation->student]);
        $query         = ViolationType::where('is_active', true)->with(['category', 'department'])->orderBy('name');
        if ($user->isDepartmentOfficer()) $query->where('department_id', $user->department_id);
        $violationTypes = $query->get();

        return view('violations.edit', compact('violation', 'students', 'violationTypes', 'activeAcademicYear'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->canManageViolations()) abort(403);
        $violation = ViolationRecord::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isDepartmentOfficer()) {
            $violation->load('violationType');
            if ($violation->violationType->department_id != $user->department_id) abort(403);
        }
        if ($user->isLicensingOfficer()) abort(403);

        $validated = $request->validate([
            'student_id'        => 'required|exists:students,id',
            'violation_type_id' => 'required|exists:violation_types,id',
            'date'              => 'required|date',
            'sanction'          => 'required|string',
            'notes'             => 'nullable|string'
        ]);

        $violationType = ViolationType::findOrFail($validated['violation_type_id']);
        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) abort(403);

        $violation->update([
            'student_id'        => $validated['student_id'],
            'violation_type_id' => $validated['violation_type_id'],
            'date'              => $validated['date'],
            'sanction'          => $validated['sanction'],
            'notes'             => $validated['notes']
        ]);

        return redirect()->route('admin.violations.index')->with('success', 'Pelanggaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (!Auth::user()->canManageViolations()) abort(403);
        $violation = ViolationRecord::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isDepartmentOfficer()) {
            $violation->load('violationType');
            if ($violation->violationType->department_id != $user->department_id) abort(403);
        }
        if ($user->isLicensingOfficer()) abort(403);
        $violation->delete();
        return redirect()->route('admin.violations.index')->with('success', 'Pelanggaran berhasil dihapus');
    }

    public function searchStudents(Request $request)
    {
        $q      = $request->input('q', '');
        $qLower = strtolower($q);
        $students = Student::with('room')
            ->where('status', 'active')
            ->where(function ($query) use ($qLower) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $qLower . '%'])
                      ->orWhereRaw('LOWER(nis) LIKE ?', ['%' . $qLower . '%']);
            })
            ->orderBy('name')->limit(30)->get()
            ->map(fn($s) => [
                'id'   => $s->id,
                'text' => $s->name,
                'info' => ($s->nis ?? '-') . ' - ' . ($s->room?->name ?? 'Belum ada kamar')
            ]);
        return response()->json(['results' => $students]);
    }
}

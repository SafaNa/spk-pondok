<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Licensing\StudentLicense;
use App\Models\Licensing\LeaveCategory;
use App\Models\Licensing\LeaveReason;
use App\Models\Master\Rayon;
use App\Models\Master\Room;
use App\Exports\LicenseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;

class LicenseReportController extends Controller
{
    private function applyFilters(Request $request)
    {
        $query = StudentLicense::with(['student.rayon', 'student.room', 'leaveCategory', 'leaveReason'])
            ->where('status', 'approved');

        if ($request->filled('tahun'))             { $query->whereYear('start_date', $request->tahun); }
        if ($request->filled('bulan'))             { $query->whereMonth('start_date', $request->bulan); }
        if ($request->filled('rayon_id'))          { $query->whereHas('student', fn($q) => $q->where('rayon_id', $request->rayon_id)); }
        if ($request->filled('room_id'))           { $query->whereHas('student', fn($q) => $q->where('room_id', $request->room_id)); }
        if ($request->filled('leave_category_id')) { $query->where('leave_category_id', $request->leave_category_id); }
        if ($request->filled('leave_reason_id'))   { $query->where('leave_reason_id', $request->leave_reason_id); }

        return $query->get()->sortByDesc('start_date')->values();
    }

    private function buildReportTitle(Request $request): string
    {
        $bulanMap = ['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni',
                     '7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        $parts = ['Laporan Perizinan'];
        if ($request->filled('bulan')) { $parts[] = $bulanMap[$request->bulan] ?? ''; }
        if ($request->filled('tahun')) { $parts[] = $request->tahun; }
        return implode(' — ', $parts);
    }

    public function index(Request $request)
    {
        // Default ke tahun ini jika belum ada filter apapun
        if (!$request->hasAny(['tahun', 'bulan', 'rayon_id', 'room_id', 'leave_category_id', 'leave_reason_id'])) {
            $request->merge(['tahun' => now()->format('Y')]);
        }

        $licenses = $this->applyFilters($request);

        // Rekapitulasi Umum
        $totalLicenses    = $licenses->count();
        $totalLate        = $licenses->filter(fn($l) => $l->is_late)->count();
        $totalOnTime      = $licenses->filter(fn($l) => $l->actual_return_date && !$l->is_late)->count();
        $totalNotReturned = $licenses->filter(fn($l) => !$l->actual_return_date && now()->startOfDay()->lte($l->end_date))->count();

        // Rekap per Kategori
        $categoryStats = $licenses->groupBy('leave_category_id')->map(function ($group) {
            return (object) [
                'name'           => $group->first()->leaveCategory->name ?? 'Tanpa Kategori',
                'approved_count' => $group->count(),
            ];
        })->sortByDesc('approved_count')->values();

        // Paginate list
        $perPage     = 20;
        $page        = $request->input('page', 1);
        $allLicenses = new LengthAwarePaginator(
            $licenses->forPage($page, $perPage),
            $licenses->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Filter lists
        $earliestYear = StudentLicense::whereNotNull('start_date')
            ->selectRaw('YEAR(MIN(start_date)) as tahun')
            ->value('tahun');
        $tahunList = $earliestYear
            ? collect(range((int) $earliestYear, (int) now()->format('Y')))->values()
            : collect();

        $rayonList    = Rayon::orderBy('name')->get(['id', 'name']);
        $roomList     = Room::orderBy('name')->get(['id', 'name', 'rayon_id']);
        $kategoriList = LeaveCategory::orderBy('order')->get(['id', 'name']);
        $alasanList   = LeaveReason::orderBy('reason')->get(['id', 'reason', 'leave_category_id']);

        return view('licensing.reports.index', compact(
            'totalLicenses', 'totalLate', 'totalOnTime', 'totalNotReturned',
            'categoryStats', 'allLicenses',
            'tahunList', 'rayonList', 'roomList', 'kategoriList', 'alasanList'
        ));
    }

    public function exportExcel(Request $request)
    {
        $licenses    = $this->applyFilters($request);
        $reportTitle = $this->buildReportTitle($request);
        $filename    = 'laporan-perizinan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new LicenseReportExport($licenses, $reportTitle), $filename);
    }

    public function exportPdf(Request $request)
    {
        $licenses    = $this->applyFilters($request);
        $reportTitle = $this->buildReportTitle($request);
        $filename    = 'laporan-perizinan-' . now()->format('Ymd-His') . '.pdf';

        $pdf = Pdf::loadView('licensing.reports.pdf', compact('licenses', 'reportTitle'))
            ->setPaper('a4', 'landscape')
            ->setOption(['margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 15, 'margin_right' => 15]);

        return $pdf->download($filename);
    }

    public function exportWord(Request $request)
    {
        $licenses    = $this->applyFilters($request);
        $reportTitle = $this->buildReportTitle($request);
        $filename    = 'laporan-perizinan-' . now()->format('Ymd-His') . '.docx';

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'orientation'  => 'landscape',
            'pageSizeW'    => 16838, // A4 landscape: 29.7cm
            'pageSizeH'    => 11906, // A4 landscape: 21cm
            'marginTop'    => 720,
            'marginBottom' => 720,
            'marginLeft'   => 720,
            'marginRight'  => 720,
        ]);

        // Judul
        $section->addText($reportTitle, ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addText('Dicetak pada ' . now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB',
            ['size' => 9, 'color' => '94a3b8'], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Tabel — A4 landscape usable width = 16838 - 720*2 = 15398 twips
        $colWidths = [500, 2500, 1200, 1200, 1500, 2398, 1200, 1200, 1200, 1500]; // total 15398 twips (approx)

        $table = $section->addTable([
            'borderSize' => 6, 'borderColor' => 'e2e8f0',
            'cellMargin' => 60,
            'width'      => 15398,
            'unit'       => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
        ]);

        $headerStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 9];
        $headerCell  = ['bgColor' => '1E3A5F', 'valign' => 'center'];
        $headers = ['No', 'Nama Santri', 'Rayon', 'Kamar', 'Kategori', 'Alasan', 'Tgl Mulai', 'Jatuh Tempo', 'Tgl Kembali', 'Status'];

        $table->addRow();
        foreach ($headers as $idx => $h) {
            $table->addCell($colWidths[$idx], $headerCell)->addText($h, $headerStyle);
        }

        foreach ($licenses as $i => $license) {
            if ($license->actual_return_date) {
                $status = $license->is_late ? 'Telat ' . $license->late_days . ' hari' : 'Tepat Waktu';
            } else {
                $status = now()->startOfDay()->gt($license->end_date) ? 'Belum Kembali (Telat)' : 'Sedang Izin';
            }

            $table->addRow();
            $table->addCell($colWidths[0])->addText($i + 1);
            $table->addCell($colWidths[1])->addText($license->student->name ?? '-');
            $table->addCell($colWidths[2])->addText($license->student->rayon->name ?? '-');
            $table->addCell($colWidths[3])->addText($license->student->room->name ?? '-');
            $table->addCell($colWidths[4])->addText($license->leaveCategory->name ?? '-');
            $table->addCell($colWidths[5])->addText($license->leaveReason->reason ?? '-');
            $table->addCell($colWidths[6])->addText($license->start_date?->format('d/m/Y') ?? '-');
            $table->addCell($colWidths[7])->addText($license->end_date?->format('d/m/Y') ?? '-');
            $table->addCell($colWidths[8])->addText($license->actual_return_date?->format('d/m/Y') ?? '-');
            $table->addCell($colWidths[9])->addText($status);
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'laporan_') . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LicenseReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithMapping, WithEvents
{
    protected $licenses;
    protected $reportTitle;

    public function __construct($licenses, string $reportTitle = 'Laporan Perizinan')
    {
        $this->licenses    = $licenses;
        $this->reportTitle = $reportTitle;
    }

    public function collection()
    {
        return $this->licenses;
    }

    public function map($license): array
    {
        static $no = 0;
        $no++;

        if ($license->actual_return_date) {
            $status = $license->is_late
                ? 'Telat ' . $license->late_days . ' hari'
                : 'Tepat Waktu';
        } else {
            $status = now()->startOfDay()->gt($license->end_date)
                ? 'Belum Kembali (Telat)'
                : 'Sedang Izin';
        }

        return [
            $no,
            $license->student->name ?? '-',
            $license->student->rayon->name ?? '-',
            $license->student->room->name ?? '-',
            $license->leaveCategory->name ?? '-',
            $license->leaveReason->reason ?? '-',
            $license->start_date?->format('d/m/Y') ?? '-',
            $license->end_date?->format('d/m/Y') ?? '-',
            $license->actual_return_date?->format('d/m/Y') ?? '-',
            $status,
        ];
    }

    public function headings(): array
    {
        return ['No', 'Nama Santri', 'Rayon', 'Kamar', 'Kategori', 'Alasan', 'Tgl Mulai', 'Jatuh Tempo', 'Tgl Kembali', 'Status'];
    }

    public function title(): string
    {
        return 'Laporan Perizinan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $lastCol    = 'J';
                $mergeRange = "A1:{$lastCol}1";

                // Sisipkan 3 baris di atas (judul, subtitel, kosong)
                $sheet->insertNewRowBefore(1, 3);

                // Baris 1: Judul
                $sheet->setCellValue('A1', $this->reportTitle);
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(22);

                // Baris 2: Subtitel
                $sheet->setCellValue('A2', 'Dicetak pada ' . now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB');
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '94a3b8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Baris 3: Kosong
                $sheet->getRowDimension(3)->setRowHeight(6);

                // Baris 4: Header kolom
                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}

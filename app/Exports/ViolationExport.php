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

class ViolationExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithMapping, WithEvents
{
    protected $records;
    protected $reportTitle;

    public function __construct($records, string $reportTitle = 'Rekap Pelanggaran')
    {
        $this->records     = $records;
        $this->reportTitle = $reportTitle;
    }

    public function collection()
    {
        return $this->records;
    }

    public function map($r): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $r->student->name ?? '-',
            $r->student->room->name ?? '-',
            $r->violationType->department->acronym ?? '-',
            $r->violationType->category->name ?? '-',
            $r->violationType->name ?? '-',
            \Illuminate\Support\Str::limit($r->sanction ?? '-', 80),
            $r->date?->format('d/m/Y') ?? '-',
            $r->sanction_status === 'completed' ? 'Selesai' : 'Belum Selesai',
        ];
    }

    public function headings(): array
    {
        return ['No', 'Nama Santri', 'Kamar', 'Bidang', 'Kategori', 'Jenis Pelanggaran', 'Sanksi', 'Tanggal', 'Status'];
    }

    public function title(): string
    {
        return 'Rekap Pelanggaran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastCol = 'I';

                $sheet->insertNewRowBefore(1, 3);

                $sheet->setCellValue('A1', $this->reportTitle);
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(22);

                $sheet->setCellValue('A2', 'Dicetak pada ' . now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB');
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '94a3b8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getRowDimension(3)->setRowHeight(6);

                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A5F']],
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

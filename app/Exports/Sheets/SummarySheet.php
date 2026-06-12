<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Collection $children) {}

    public function title(): string { return 'Ringkasan'; }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 25, 'C' => 12, 'D' => 15, 'E' => 30, 'F' => 22, 'G' => 20, 'H' => 15, 'I' => 12];
    }

    public function array(): array
    {
        $severityLabels = [
            'normal' => 'Belum Terindikasi',
            'light'  => 'Terindikasi Ringan',
            'medium' => 'Terindikasi Sedang',
            'heavy'  => 'Terindikasi Kuat',
        ];

        $rows = [];
        $rows[] = ['LAPORAN ASESMEN IDENTIFIKASI ABK — CHILD SEE'];
        $rows[] = ['Tanggal Export: ' . now()->format('d F Y H:i')];
        $rows[] = [''];
        $rows[] = ['No', 'Nama Anak', 'Usia', 'Sekolah', 'Kategori Terakhir', 'Hasil Terakhir', 'Skor', 'Tingkat', 'Tanggal Asesmen'];

        $no = 1;
        foreach ($this->children as $child) {
            $last = $child->assessments->first();
            if ($last) {
                $rows[] = [
                    $no++,
                    $child->full_name,
                    \Carbon\Carbon::parse($child->birth_date)->age . ' thn',
                    $child->school_name ?? '-',
                    $last->category->name ?? '-',
                    $last->result_label ?? '-',
                    $last->total_score ?? 0,
                    $severityLabels[$last->severity_level] ?? '-',
                    \Carbon\Carbon::parse($last->created_at)->format('d/m/Y'),
                ];
            } else {
                $rows[] = [
                    $no++,
                    $child->full_name,
                    \Carbon\Carbon::parse($child->birth_date)->age . ' thn',
                    $child->school_name ?? '-',
                    'Belum ada asesmen', '', '', '', '',
                ];
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = 4 + $this->children->count();

        // Title
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4A3769']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['size' => 10, 'color' => ['rgb' => '666666']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0EEF5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8E77AB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            "A4:I{$lastRow}" => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D8D0E8']],
                ],
            ],
        ];
    }
}

<?php

namespace App\Exports\Sheets;

use App\Models\Child;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ChildSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Child $child) {}

    public function title(): string
    {
        return \Illuminate\Support\Str::limit($this->child->full_name, 28, '');
    }

    public function columnWidths(): array
    {
        return ['A' => 28, 'B' => 35, 'C' => 14, 'D' => 22, 'E' => 22];
    }

    public function array(): array
    {
        $child = $this->child;
        $severityLabels = [
            'normal' => 'Belum Terindikasi',
            'light'  => 'Terindikasi Ringan',
            'medium' => 'Terindikasi Sedang',
            'heavy'  => 'Terindikasi Kuat',
        ];

        $rows = [];
        $rows[] = ['LAPORAN ASESMEN ANAK — CHILD SEE'];
        $rows[] = [''];

        // Child info block
        $rows[] = ['PROFIL ANAK', ''];
        $rows[] = ['Nama Lengkap', $child->full_name];
        $rows[] = ['Nama Panggilan', $child->nick_name ?? '-'];
        $rows[] = ['Jenis Kelamin', $child->gender === 'male' ? 'Laki-laki' : 'Perempuan'];
        $rows[] = ['Tanggal Lahir', $child->birth_date ? \Carbon\Carbon::parse($child->birth_date)->format('d F Y') . ' (' . \Carbon\Carbon::parse($child->birth_date)->age . ' tahun)' : '-'];
        $rows[] = ['Sekolah', $child->school_name ?? '-'];
        $rows[] = ['Kelas', $child->class_name ?? '-'];
        $rows[] = ['Nama Orang Tua', $child->parent_name ?? '-'];
        $rows[] = ['Tanggal Export', now()->format('d F Y H:i')];
        $rows[] = [''];

        if ($child->assessments->isEmpty()) {
            $rows[] = ['Belum ada asesmen yang diselesaikan.'];
            return $rows;
        }

        foreach ($child->assessments as $idx => $assessment) {
            $rows[] = ['ASESMEN #' . ($idx + 1) . ' — ' . ($assessment->category->name ?? '-')];
            $rows[] = ['Kode Asesmen', $assessment->assessment_code, 'Tanggal', \Carbon\Carbon::parse($assessment->created_at)->format('d/m/Y')];
            $rows[] = ['Hasil', $assessment->result_label ?? '-', 'Total Skor', $assessment->total_score ?? 0];
            $rows[] = ['Tingkat', $severityLabels[$assessment->severity_level] ?? '-'];
            $rows[] = [''];

            if ($assessment->domainScores->isNotEmpty()) {
                $rows[] = ['Domain', 'Skor Domain', 'Hasil Domain'];
                foreach ($assessment->domainScores as $ds) {
                    $rows[] = [
                        $ds->domain->name ?? '-',
                        $ds->total_score ?? 0,
                        $ds->result_label ?? '-',
                    ];
                }
                $rows[] = [''];
            }

            if ($assessment->result_description) {
                $rows[] = ['Keterangan', $assessment->result_description];
                $rows[] = [''];
            }
            if ($assessment->recommendation) {
                $rows[] = ['Rekomendasi', $assessment->recommendation];
                $rows[] = [''];
            }

            $rows[] = ['---'];
            $rows[] = [''];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4A3769']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '4A3769']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0EEF5']],
            ],
        ];
    }
}

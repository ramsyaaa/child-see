<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Child;
use App\Exports\ChildrenExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    // Single assessment PDF
    public function assessmentPdf(Assessment $assessment)
    {
        abort_unless($assessment->user_id === auth()->id(), 403);

        $assessment->load([
            'child', 'category', 'domainScores.domain',
            'answers.questionnaire.domain', 'answers.answerOption',
        ]);

        $pdf = Pdf::loadView('member.exports.assessment-pdf', compact('assessment'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true, 'isLocalFileEnabled' => true]);

        $filename = 'Asesmen-' . $assessment->assessment_code . '-' . Str::slug($assessment->child->full_name) . '.pdf';

        return $pdf->download($filename);
    }

    // All assessments for one child as PDF
    public function childPdf(Child $child)
    {
        abort_unless($child->user_id === auth()->id(), 403);

        $assessments = Assessment::where('child_id', $child->id)
            ->where('status', 'completed')
            ->with(['category', 'domainScores.domain', 'answers.questionnaire.domain', 'answers.answerOption'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('member.exports.child-pdf', compact('child', 'assessments'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true, 'isLocalFileEnabled' => true]);

        $filename = 'Laporan-' . Str::slug($child->full_name) . '.pdf';

        return $pdf->download($filename);
    }

    // All children → one Excel with N sheets
    public function childrenExcel()
    {
        $children = Child::where('user_id', auth()->id())
            ->with(['assessments' => fn($q) => $q->where('status', 'completed')
                ->with(['category', 'domainScores.domain'])
                ->latest()])
            ->get();

        $filename = 'Laporan-Asesmen-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new ChildrenExport($children), $filename);
    }

    // All children → ZIP of individual PDFs
    public function childrenPdfZip()
    {
        $children = Child::where('user_id', auth()->id())
            ->with(['assessments' => fn($q) => $q->where('status', 'completed')
                ->with(['category', 'domainScores.domain', 'answers.questionnaire.domain', 'answers.answerOption'])
                ->latest()])
            ->get();

        $zipPath = storage_path('app/temp/laporan-' . now()->format('YmdHis') . '.zip');
        @mkdir(dirname($zipPath), 0755, true);

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($children as $child) {
            $assessments = $child->assessments;
            $pdf = Pdf::loadView('member.exports.child-pdf', compact('child', 'assessments'))
                ->setPaper('a4', 'portrait')
                ->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true, 'isLocalFileEnabled' => true]);

            $pdfContent = $pdf->output();
            $filename = Str::slug($child->full_name) . '.pdf';
            $zip->addFromString($filename, $pdfContent);
        }

        $zip->close();

        return response()->download($zipPath, 'Laporan-Semua-Anak-' . now()->format('Ymd') . '.zip')
            ->deleteFileAfterSend(true);
    }
}

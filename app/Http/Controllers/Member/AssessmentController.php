<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Category;
use App\Models\Child;
use App\Services\AssessmentService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct(private AssessmentService $assessmentService)
    {
    }

    public function selectChild()
    {
        $child      = Child::where('user_id', auth()->id())->first();
        $categories = Category::active()->get();

        return view('member.assessment.select-child', compact('child', 'categories'));
    }

    public function start(Request $r)
    {
        // child_id may come from form or be auto-resolved from single child
        $child = Child::where('user_id', auth()->id())->firstOrFail();

        $r->validate([
            'category_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->where('is_active', true)->exists()) {
                        $fail('Kategori yang dipilih tidak valid.');
                    }
                },
            ],
        ]);

        $category = Category::findOrFail($r->category_id);

        $assessment = $this->assessmentService->start(auth()->user(), $child, $category);

        return redirect()->route('member.assessment.questions', $assessment->id);
    }

    public function questions(Assessment $assessment)
    {
        abort_unless($assessment->user_id === auth()->id(), 403);

        $questionnaires = $assessment->category
            ->questionnaires()
            ->with(['domain', 'answerOptions'])
            ->where('is_active', true)
            ->orderBy('domain_id')
            ->orderBy('sort_order')
            ->get();

        // Group by domain name for display; each item has domain relation loaded
        $grouped = $questionnaires->groupBy(fn($q) => $q->domain?->name ?? 'Umum');

        return view('member.assessment.questions', compact('assessment', 'grouped', 'questionnaires'));
    }

    public function submit(Request $r, Assessment $assessment)
    {
        abort_unless($assessment->user_id === auth()->id(), 403);

        $r->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required',
        ]);

        $this->assessmentService->saveAnswers($assessment, $r->answers);
        $this->assessmentService->finalize($assessment);

        return redirect()->route('member.assessment.result', $assessment->id);
    }

    public function result(Assessment $assessment)
    {
        abort_unless($assessment->user_id === auth()->id(), 403);

        $assessment->load(['child', 'category', 'domainScores.domain', 'answers']);

        return view('member.assessment.result', compact('assessment'));
    }

    public function history()
    {
        $assessments = Assessment::where('user_id', auth()->id())
            ->with(['child', 'category'])
            ->when(request('category_id'), fn($q, $id) => $q->where('category_id', $id))
            ->latest()
            ->paginate(10);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('member.assessment.history', compact('assessments', 'categories'));
    }
}
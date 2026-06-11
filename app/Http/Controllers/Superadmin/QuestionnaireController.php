<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AnswerOption;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $r)
    {
        $query = Questionnaire::with(['category', 'domain'])->orderBy('sort_order');

        if ($r->filled('category_id')) {
            $query->where('category_id', $r->category_id);
        }

        if ($r->filled('domain_id')) {
            $query->where('domain_id', $r->domain_id);
        }

        $questionnaires = $query->get();
        $categories     = Category::orderBy('name')->get();
        $domains        = Domain::orderBy('name')->get();

        return view('superadmin.questionnaires.index', compact('questionnaires', 'categories', 'domains'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $domains    = Domain::orderBy('name')->get();

        return view('superadmin.questionnaires.create', compact('categories', 'domains'));
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'category_id'   => 'nullable|integer|exists:categories,id',
            'domain_id'     => 'nullable|integer|exists:domains,id',
            'question'      => 'required|string',
            'question_type' => 'required|in:yes_no,scale,text',
            'weight'        => 'nullable|numeric|min:0',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
        ]);

        $validated['is_active'] = $r->boolean('is_active');

        $questionnaire = Questionnaire::create($validated);

        if ($r->filled('answer_options') && is_array($r->answer_options)) {
            foreach ($r->answer_options as $index => $option) {
                if (!empty($option['label'])) {
                    AnswerOption::create([
                        'questionnaire_id' => $questionnaire->id,
                        'label'            => $option['label'],
                        'value'            => $option['value'] ?? null,
                        'score'            => $option['score'] ?? 0,
                        'sort_order'       => $index,
                    ]);
                }
            }
        }

        return redirect()->route('superadmin.questionnaires.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(Questionnaire $questionnaire)
    {
        $categories = Category::orderBy('name')->get();
        $domains    = Domain::orderBy('name')->get();
        $questionnaire->load('answerOptions');

        return view('superadmin.questionnaires.edit', compact('questionnaire', 'categories', 'domains'));
    }

    public function update(Request $r, Questionnaire $questionnaire)
    {
        $validated = $r->validate([
            'category_id'   => 'nullable|integer|exists:categories,id',
            'domain_id'     => 'nullable|integer|exists:domains,id',
            'question'      => 'required|string',
            'question_type' => 'required|in:yes_no,scale,text',
            'weight'        => 'nullable|numeric|min:0',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
        ]);

        $validated['is_active'] = $r->boolean('is_active');

        $questionnaire->update($validated);

        if ($r->filled('answer_options') && is_array($r->answer_options)) {
            $questionnaire->answerOptions()->delete();

            if (true) {
                foreach ($r->answer_options as $index => $option) {
                    if (!empty($option['label'])) {
                        AnswerOption::create([
                            'questionnaire_id' => $questionnaire->id,
                            'label'            => $option['label'],
                            'value'            => $option['value'] ?? null,
                            'score'            => $option['score'] ?? 0,
                            'sort_order'       => $index,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('superadmin.questionnaires.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->answerOptions()->delete();
        $questionnaire->delete();

        return redirect()->route('superadmin.questionnaires.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
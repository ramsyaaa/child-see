<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRule;
use App\Models\Category;
use App\Models\Domain;
use Illuminate\Http\Request;

class AssessmentRuleController extends Controller
{
    public function index()
    {
        $rules = AssessmentRule::with(['category', 'domain'])->orderBy('category_id')->orderBy('severity_level')->get();

        return view('superadmin.rules.index', compact('rules'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $domains    = Domain::orderBy('name')->get();

        return view('superadmin.rules.create', compact('categories', 'domains'));
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'category_id'    => 'required|integer|exists:categories,id',
            'domain_id'      => 'nullable|integer|exists:domains,id',
            'label'          => 'required|string|max:255',
            'severity_level' => 'required|in:normal,light,medium,heavy',
            'min_score'      => 'nullable|numeric',
            'max_score'      => 'nullable|numeric',
            'color'          => 'nullable|string|max:20',
            'description'    => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        AssessmentRule::create($validated);

        return redirect()->route('superadmin.rules.index')
            ->with('success', 'Aturan penilaian berhasil ditambahkan.');
    }

    public function edit(AssessmentRule $rule)
    {
        $categories = Category::orderBy('name')->get();
        $domains    = Domain::orderBy('name')->get();

        return view('superadmin.rules.edit', compact('rule', 'categories', 'domains'));
    }

    public function update(Request $r, AssessmentRule $rule)
    {
        $validated = $r->validate([
            'category_id'    => 'required|integer|exists:categories,id',
            'domain_id'      => 'nullable|integer|exists:domains,id',
            'label'          => 'required|string|max:255',
            'severity_level' => 'required|in:normal,light,medium,heavy',
            'min_score'      => 'nullable|numeric',
            'max_score'      => 'nullable|numeric',
            'color'          => 'nullable|string|max:20',
            'description'    => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        $rule->update($validated);

        return redirect()->route('superadmin.rules.index')
            ->with('success', 'Aturan penilaian berhasil diperbarui.');
    }

    public function destroy(AssessmentRule $rule)
    {
        $rule->delete();

        return redirect()->route('superadmin.rules.index')
            ->with('success', 'Aturan penilaian berhasil dihapus.');
    }
}
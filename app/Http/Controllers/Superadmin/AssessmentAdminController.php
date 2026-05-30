<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Category;
use Illuminate\Http\Request;

class AssessmentAdminController extends Controller
{
    public function index(Request $r)
    {
        $query = Assessment::with(['child', 'category', 'user'])->latest();

        if ($r->filled('category_id')) {
            $query->where('category_id', $r->category_id);
        }

        if ($r->filled('severity_level')) {
            $query->where('severity_level', $r->severity_level);
        }

        $assessments = $query->paginate(15)->withQueryString();
        $categories  = Category::orderBy('name')->get();

        return view('superadmin.assessments.index', compact('assessments', 'categories'));
    }

    public function show(Assessment $assessment)
    {
        $assessment->load([
            'child',
            'category',
            'user',
            'domainScores.domain',
            'answers.questionnaire',
            'answers.questionnaire.domain',
        ]);

        return view('superadmin.assessments.show', compact('assessment'));
    }
}
<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class InkluQuestionsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'penglihatan');
        $questions = Question::where('type', $type)->orderBy('sort_order')->get();
        return view('superadmin.inklu.questions', compact('questions', 'type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'       => 'required|in:penglihatan,pendengaran,intelektual',
            'body'       => 'required|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['active'] = true;
        $data['sort_order'] = $data['sort_order'] ?? Question::where('type', $data['type'])->max('sort_order') + 1;
        Question::create($data);

        return back()->with('success', 'Pertanyaan ditambahkan.');
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'body'       => 'required|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'active'     => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);
        $question->update($data);

        return back()->with('success', 'Pertanyaan diperbarui.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Pertanyaan dihapus.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        foreach ($request->input('order') as $i => $id) {
            Question::where('id', $id)->update(['sort_order' => $i + 1]);
        }
        return response()->json(['ok' => true]);
    }
}

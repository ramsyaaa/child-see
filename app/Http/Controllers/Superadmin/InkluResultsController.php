<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\IdentificationResult;
use Illuminate\Http\Request;

class InkluResultsController extends Controller
{
    public function index(Request $request)
    {
        $query = IdentificationResult::with('user')->latest();

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('child_name', 'like', "%$search%")
                  ->orWhere('filler_name', 'like', "%$search%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%$search%"));
            });
        }

        $results = $query->paginate(20)->withQueryString();

        return view('superadmin.inklu.results', compact('results'));
    }

    public function show(IdentificationResult $result)
    {
        $result->load('user');
        return view('superadmin.inklu.result-detail', compact('result'));
    }

    public function destroy(IdentificationResult $result)
    {
        $result->delete();
        return back()->with('success', 'Hasil identifikasi dihapus.');
    }
}

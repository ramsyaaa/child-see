<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MasterClass;
use Illuminate\Http\Request;

class BrowseClassesController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterClass::query()->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $masterClasses = $query->paginate(12);
        
        // Get unique categories for filter
        $categories = MasterClass::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('member.browse-classes.index', compact('masterClasses', 'categories'));
    }

    public function show(MasterClass $masterClass)
    {
        // Get upcoming batch classes for this master class
        $upcomingClasses = $masterClass->batchClasses()
            ->with(['instructor', 'room'])
            ->where('date', '>=', now())
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return view('member.browse-classes.show', compact('masterClass', 'upcomingClasses'));
    }
}


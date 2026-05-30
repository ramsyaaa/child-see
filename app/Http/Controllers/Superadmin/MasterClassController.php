<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\MasterClass;
use Illuminate\Http\Request;

class MasterClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $masterClasses = MasterClass::latest()->paginate(10);
        return view('superadmin.master-classes.index', compact('masterClasses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.master-classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'default_duration' => 'required|integer|min:15|max:180',
            'is_active' => 'boolean',
            'color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['is_active'] = $request->has('is_active');

        MasterClass::create($validated);

        return redirect()->route('superadmin.master-classes.index')
            ->with('success', 'Master Class created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterClass $masterClass)
    {
        $masterClass->load('batchClasses');
        return view('superadmin.master-classes.show', compact('masterClass'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterClass $masterClass)
    {
        return view('superadmin.master-classes.edit', compact('masterClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterClass $masterClass)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'default_duration' => 'required|integer|min:15|max:180',
            'is_active' => 'boolean',
            'color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Handle checkbox (unchecked checkboxes are not sent in the request)
        $validated['is_active'] = $request->has('is_active');

        $masterClass->update($validated);

        return redirect()->route('superadmin.master-classes.index')
            ->with('success', 'Master Class updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterClass $masterClass)
    {
        // Check if master class has any batch classes
        if ($masterClass->batchClasses()->count() > 0) {
            return redirect()->route('superadmin.master-classes.index')
                ->with('error', 'Cannot delete Master Class that has scheduled batch classes!');
        }

        $masterClass->delete();

        return redirect()->route('superadmin.master-classes.index')
            ->with('success', 'Master Class deleted successfully!');
    }
}


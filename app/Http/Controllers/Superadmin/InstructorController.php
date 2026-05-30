<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructors = Instructor::latest()->paginate(10);
        return view('superadmin.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.instructors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('instructors', 'public');
            $validated['photo_url'] = $path;
        }

        Instructor::create($validated);

        return redirect()->route('superadmin.instructors.index')
            ->with('success', 'Instructor created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        $instructor->load('batchClasses');
        return view('superadmin.instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        return view('superadmin.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($instructor->photo_url) {
                Storage::disk('public')->delete($instructor->photo_url);
            }
            $path = $request->file('photo')->store('instructors', 'public');
            $validated['photo_url'] = $path;
        }

        $instructor->update($validated);

        return redirect()->route('superadmin.instructors.index')
            ->with('success', 'Instructor updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        // Check if instructor has any batch classes
        if ($instructor->batchClasses()->count() > 0) {
            return redirect()->route('superadmin.instructors.index')
                ->with('error', 'Cannot delete Instructor that has scheduled batch classes!');
        }

        // Delete photo if exists
        if ($instructor->photo_url) {
            Storage::disk('public')->delete($instructor->photo_url);
        }

        $instructor->delete();

        return redirect()->route('superadmin.instructors.index')
            ->with('success', 'Instructor deleted successfully!');
    }
}


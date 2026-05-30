<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    public function index()
    {
        $child = Child::where('user_id', auth()->id())
            ->with(['assessments' => fn($q) => $q->where('status', 'completed')->with('category')->latest()->limit(3)])
            ->first();

        return view('member.children.index', compact('child'));
    }

    public function create()
    {
        // Only 1 child allowed per user
        if (Child::where('user_id', auth()->id())->exists()) {
            return redirect()->route('member.children.index')
                ->with('info', 'Setiap akun hanya dapat memiliki satu data anak. Edit data anak yang sudah ada.');
        }

        return view('member.children.create');
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'full_name'    => 'required|string|max:255',
            'nick_name'    => 'nullable|string|max:100',
            'gender'       => 'nullable|in:male,female',
            'birth_date'   => 'required|date',
            'birth_place'  => 'nullable|string|max:150',
            'parent_name'  => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:30',
            'school_name'  => 'nullable|string|max:255',
            'class_name'   => 'nullable|string|max:100',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
            'photo'        => 'nullable|image|max:2048',
        ]);

        // Enforce single child per user
        if (Child::where('user_id', auth()->id())->exists()) {
            return redirect()->route('member.children.index')
                ->with('info', 'Setiap akun hanya dapat memiliki satu data anak.');
        }

        $validated['user_id'] = auth()->id();

        if ($r->hasFile('photo')) {
            $validated['photo'] = $r->file('photo')->store('children', 'public');
        }

        Child::create($validated);

        return redirect()->route('member.children.index')
            ->with('success', 'Data anak berhasil ditambahkan.');
    }

    public function edit(Child $child)
    {
        abort_unless($child->user_id === auth()->id(), 403);

        return view('member.children.edit', compact('child'));
    }

    public function update(Request $r, Child $child)
    {
        abort_unless($child->user_id === auth()->id(), 403);

        $validated = $r->validate([
            'full_name'    => 'required|string|max:255',
            'nick_name'    => 'nullable|string|max:100',
            'gender'       => 'nullable|in:male,female',
            'birth_date'   => 'required|date',
            'birth_place'  => 'nullable|string|max:150',
            'parent_name'  => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:30',
            'school_name'  => 'nullable|string|max:255',
            'class_name'   => 'nullable|string|max:100',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
            'photo'        => 'nullable|image|max:2048',
        ]);

        if ($r->hasFile('photo')) {
            if ($child->photo) {
                Storage::disk('public')->delete($child->photo);
            }
            $validated['photo'] = $r->file('photo')->store('children', 'public');
        }

        $child->update($validated);

        return redirect()->route('member.children.index')
            ->with('success', 'Data anak berhasil diperbarui.');
    }

    public function destroy(Child $child)
    {
        abort_unless($child->user_id === auth()->id(), 403);

        if ($child->photo) {
            Storage::disk('public')->delete($child->photo);
        }

        $child->delete();

        return redirect()->route('member.children.index')
            ->with('success', 'Data anak berhasil dihapus.');
    }
}
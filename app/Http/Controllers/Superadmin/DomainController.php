<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index(Request $r)
    {
        $query = Domain::with('category')->orderBy('sort_order')->orderBy('name');

        if ($r->filled('category_id')) {
            $query->where('category_id', $r->category_id);
        }

        $domains    = $query->get();
        $categories = Category::orderBy('name')->get();

        return view('superadmin.domains.index', compact('domains', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('superadmin.domains.create', compact('categories'));
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $r->boolean('is_active');

        Domain::create($validated);

        return redirect()->route('superadmin.domains.index')
            ->with('success', 'Domain berhasil ditambahkan.');
    }

    public function edit(Domain $domain)
    {
        $categories = Category::orderBy('name')->get();

        return view('superadmin.domains.edit', compact('domain', 'categories'));
    }

    public function update(Request $r, Domain $domain)
    {
        $validated = $r->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $r->boolean('is_active');

        $domain->update($validated);

        return redirect()->route('superadmin.domains.index')
            ->with('success', 'Domain berhasil diperbarui.');
    }

    public function destroy(Domain $domain)
    {
        if ($domain->questionnaires()->exists()) {
            return redirect()->back()
                ->with('error', 'Domain tidak dapat dihapus karena memiliki pertanyaan terkait. Hapus pertanyaan terlebih dahulu.');
        }

        $domain->delete();

        return redirect()->route('superadmin.domains.index')
            ->with('success', 'Domain berhasil dihapus.');
    }
}
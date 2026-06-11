<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('superadmin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('superadmin.categories.create');
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'name'                => 'required|string|max:255',
            'slug'                => 'required|string|max:255|unique:categories,slug',
            'type'                => 'required|string|max:100',
            'group'               => 'nullable|string|max:100',
            'description'         => 'nullable|string',
            'icon'                => 'nullable|string|max:100',
            'color'               => 'nullable|string|max:20',
            'result_illustration' => 'nullable|string|max:500',
            'sort_order'          => 'nullable|integer',
            'is_active'           => 'nullable|boolean',
        ]);

        $validated['is_active'] = $r->boolean('is_active');

        Category::create($validated);

        return redirect()->route('superadmin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('superadmin.categories.edit', compact('category'));
    }

    public function update(Request $r, Category $category)
    {
        $validated = $r->validate([
            'name'                => 'required|string|max:255',
            'slug'                => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'type'                => 'required|string|max:100',
            'group'               => 'nullable|string|max:100',
            'description'         => 'nullable|string',
            'icon'                => 'nullable|string|max:100',
            'color'               => 'nullable|string|max:20',
            'result_illustration' => 'nullable|string|max:500',
            'sort_order'          => 'nullable|integer',
            'is_active'           => 'nullable|boolean',
        ]);

        $validated['is_active'] = $r->boolean('is_active');

        $category->update($validated);

        return redirect()->route('superadmin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->questionnaires()->exists() || $category->domains()->exists()) {
            return redirect()->back()
                ->with('error', 'Kategori tidak dapat dihapus karena memiliki domain atau kuesioner terkait.');
        }

        $category->delete();

        return redirect()->route('superadmin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
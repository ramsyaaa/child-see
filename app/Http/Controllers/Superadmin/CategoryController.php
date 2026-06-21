<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct(private ImageCompressionService $imageCompression)
    {
    }

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
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:categories,slug',
            'type'                  => 'required|string|max:100',
            'group'                 => 'nullable|string|max:100',
            'description'           => 'nullable|string',
            'icon'                  => 'nullable|string|max:100',
            'color'                 => 'nullable|string|max:20',
            'result_illustration_file' => 'nullable|image|max:5120',
            'sort_order'            => 'nullable|integer',
            'is_active'             => 'nullable|boolean',
        ]);

        unset($validated['result_illustration_file']);
        $validated['is_active'] = $r->boolean('is_active');

        if ($r->hasFile('result_illustration_file')) {
            $path = $this->imageCompression->storeCompressed(
                $r->file('result_illustration_file'), 'hasil-analisa', 600
            );
            $validated['result_illustration'] = 'storage/' . $path;
        }

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
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'type'                  => 'required|string|max:100',
            'group'                 => 'nullable|string|max:100',
            'description'           => 'nullable|string',
            'icon'                  => 'nullable|string|max:100',
            'color'                 => 'nullable|string|max:20',
            'result_illustration_file' => 'nullable|image|max:5120',
            'remove_illustration'   => 'nullable|boolean',
            'sort_order'            => 'nullable|integer',
            'is_active'             => 'nullable|boolean',
        ]);

        unset($validated['result_illustration_file'], $validated['remove_illustration']);
        $validated['is_active'] = $r->boolean('is_active');

        if ($r->hasFile('result_illustration_file')) {
            $oldPath = $category->result_illustration;
            $path = $this->imageCompression->storeCompressed(
                $r->file('result_illustration_file'), 'hasil-analisa', 600
            );
            $validated['result_illustration'] = 'storage/' . $path;
            // Only delete the old file if it lived in the uploadable storage disk (not a bundled default asset)
            if ($oldPath && str_starts_with($oldPath, 'storage/hasil-analisa/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldPath));
            }
        } elseif ($r->boolean('remove_illustration')) {
            $validated['result_illustration'] = null;
        }

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
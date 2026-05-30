<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('superadmin.products.index', compact('products'));
    }

    public function create()
    {
        return view('superadmin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:subscription,dropin,bundle',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'quota' => 'nullable|integer|min:1',
            'quota_type' => 'nullable|in:unlimited,limited',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        if ($validated['quota_type'] == 'unlimited') {
            $validated['quota'] = null;
        }

        Product::create($validated);

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load('subscriptions');
        return view('superadmin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('superadmin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:subscription,dropin,bundle',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'quota' => 'nullable|integer|min:1',
            'quota_type' => 'nullable|in:unlimited,limited',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        if ($validated['quota_type'] == 'unlimited') {
            $validated['quota'] = null;
        }

        $product->update($validated);

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->subscriptions()->count() > 0) {
            return redirect()->route('superadmin.products.index')
                ->with('error', 'Cannot delete Product that has active subscriptions!');
        }

        $product->delete();

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}


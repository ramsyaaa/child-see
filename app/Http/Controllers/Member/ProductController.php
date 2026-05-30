<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $products = $query->orderBy('type')
            ->orderBy('price')
            ->paginate(12);

        return view('member.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        return view('member.products.show', compact('product'));
    }
}


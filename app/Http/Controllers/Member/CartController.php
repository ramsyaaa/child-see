<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')
            ->where('user_id', Auth::id())
            ->first();

        return view('member.cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if cart already has an item (single item cart)
        $existingCart = Cart::where('user_id', Auth::id())->first();
        
        if ($existingCart) {
            // Update existing cart item
            $existingCart->product_id = $request->product_id;
            $existingCart->quantity = 1;
            $existingCart->save();
            
            return redirect()->route('member.cart.index')
                ->with('success', 'Cart updated successfully!');
        }

        // Create new cart item
        $product = Product::findOrFail($request->product_id);
        
        $cart = new Cart();
        $cart->user_id = Auth::id();
        $cart->product_id = $product->id;
        $cart->quantity = 1;
        $cart->save();

        return redirect()->route('member.cart.index')
            ->with('success', 'Product added to cart!');
    }

    public function destroy()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('member.cart.index')
            ->with('success', 'Cart cleared!');
    }
}


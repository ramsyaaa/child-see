<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'product'])
            ->latest()
            ->paginate(15);
        
        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->get();
        $products = Product::where('type', 'subscription')->active()->get();
        
        return view('superadmin.subscriptions.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quota_allocated' => 'nullable|integer|min:1',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $validated['quota_used'] = 0;

        Subscription::create($validated);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'product', 'bookings']);
        return view('superadmin.subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $users = User::where('role', 'member')->get();
        $products = Product::where('type', 'subscription')->active()->get();
        
        return view('superadmin.subscriptions.edit', compact('subscription', 'users', 'products'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quota_allocated' => 'nullable|integer|min:1',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $subscription->update($validated);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }
}


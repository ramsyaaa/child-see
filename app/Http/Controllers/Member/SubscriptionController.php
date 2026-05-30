<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        if ($subscription->user_id != Auth::id()) {
            abort(403);
        }

        $bookings = $subscription->bookings()
            ->with(['batchClass.masterClass', 'batchClass.instructor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.subscriptions.show', compact('subscription', 'bookings'));
    }
}


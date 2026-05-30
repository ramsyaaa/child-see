<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbandonedCartController extends Controller
{
    public function index(Request $request)
    {
        $query = AbandonedCart::with(['user', 'product'])->latest('captured_at');

        // Date range filter
        if ($request->filled('from')) {
            $query->where('captured_at', '>=', Carbon::parse($request->from)->startOfDay());
        }
        if ($request->filled('to')) {
            $query->where('captured_at', '<=', Carbon::parse($request->to)->endOfDay());
        }

        // Reason filter
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $abandonedCarts = $query->paginate(20)->withQueryString();

        // Summary stats
        $totalAbandoned   = AbandonedCart::count();
        $totalValue       = AbandonedCart::sum('price');
        $thisMonthCount   = AbandonedCart::whereMonth('captured_at', now()->month)
                               ->whereYear('captured_at', now()->year)->count();
        $thisMonthValue   = AbandonedCart::whereMonth('captured_at', now()->month)
                               ->whereYear('captured_at', now()->year)->sum('price');

        $reasons = AbandonedCart::select('reason')->distinct()->pluck('reason');

        return view('superadmin.abandoned-carts.index', compact(
            'abandonedCarts', 'totalAbandoned', 'totalValue',
            'thisMonthCount', 'thisMonthValue', 'reasons'
        ));
    }
}

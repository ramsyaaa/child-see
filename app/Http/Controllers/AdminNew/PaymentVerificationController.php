<?php

namespace App\Http\Controllers\AdminNew;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'bankAccount', 'items.product']);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        } else {
            $query->where('payment_status', 'pending');
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin-new.payment-verification.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'bankAccount', 'items.product']);
        return view('admin-new.payment-verification.show', compact('transaction'));
    }

    public function verify(Request $request, Transaction $transaction)
    {
        if ($transaction->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending payments can be verified!');
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'payment_status' => 'paid',
                'verified_at'    => now(),
                'verified_by'    => auth()->id(),
            ]);

            // Activate subscriptions for each purchased product
            foreach ($transaction->items as $item) {
                $product = $item->product;
                if (!$product) continue;

                if (in_array($product->type, ['subscription', 'bundle'])) {
                    Subscription::create([
                        'user_id'         => $transaction->user_id,
                        'product_id'      => $item->product_id,
                        'start_date'      => Carbon::today(),
                        'end_date'        => Carbon::today()->addDays($product->duration_days ?? 30),
                        'quota_allocated' => $product->quota_type === 'unlimited' ? null : ($product->quota ?? 0),
                        'quota_used'      => 0,
                        'status'          => 'active',
                    ]);
                }
                // dropin products: quota is consumed per booking — no subscription record needed
            }

            DB::commit();

            return redirect()->route('admin.payment-verification.index')
                ->with('success', 'Payment verified and subscription activated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Transaction $transaction)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($transaction->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending payments can be rejected!');
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'payment_status'   => 'failed',
                'rejection_reason' => $request->rejection_reason,
                'verified_at'      => now(),
                'verified_by'      => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.payment-verification.index')
                ->with('success', 'Payment rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }
}

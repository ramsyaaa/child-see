<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\BankAccount;
use App\Models\PaymentGatewayConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return redirect()->route('member.products.index')
                ->with('error', 'Your cart is empty!');
        }

        $bankAccounts  = BankAccount::where('is_active', true)->get();
        $gatewayConfig = PaymentGatewayConfig::getConfig();

        return view('member.checkout.index', compact('cart', 'bankAccounts', 'gatewayConfig'));
    }

    /**
     * AJAX: validate a coupon code against the current cart total.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Coupon code not found.'], 422);
        }

        if (!$coupon->isValid((float) $request->subtotal)) {
            $reason = 'This coupon is no longer valid.';
            if (!$coupon->is_active) {
                $reason = 'This coupon has been deactivated.';
            } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                $reason = 'This coupon has expired.';
            } elseif ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
                $reason = 'This coupon has reached its usage limit.';
            } elseif ($coupon->min_purchase !== null && (float) $request->subtotal < $coupon->min_purchase) {
                $reason = 'Minimum purchase of Rp ' . number_format($coupon->min_purchase, 0, ',', '.') . ' required.';
            }
            return response()->json(['valid' => false, 'message' => $reason], 422);
        }

        $discount = $coupon->calculateDiscount((float) $request->subtotal);
        $finalTotal = (float) $request->subtotal - $discount;

        return response()->json([
            'valid'        => true,
            'coupon_id'    => $coupon->id,
            'code'         => $coupon->code,
            'description'  => $coupon->description,
            'type'         => $coupon->type,
            'value'        => $coupon->value,
            'discount'     => $discount,
            'final_total'  => $finalTotal,
            'message'      => 'Coupon applied! You save Rp ' . number_format($discount, 0, ',', '.') . '.',
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method'  => 'required|in:offline',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'payment_proof'   => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'coupon_code'     => 'nullable|string|max:50',
        ]);

        $cart = Cart::with('product')->where('user_id', Auth::id())->first();

        if (!$cart) {
            return redirect()->route('member.products.index')
                ->with('error', 'Your cart is empty!');
        }

        $subtotal       = (float) $cart->product->price;
        $discountAmount = 0;
        $coupon         = null;

        // Re-validate coupon server-side
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            } else {
                // Coupon became invalid between apply and submit — ignore silently
                $coupon         = null;
                $discountAmount = 0;
            }
        }

        $totalAmount = max(0, $subtotal - $discountAmount);

        DB::beginTransaction();
        try {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

            $transaction = Transaction::create([
                'user_id'            => Auth::id(),
                'transaction_number' => 'TRX-' . strtoupper(uniqid()),
                'total_amount'       => $totalAmount,
                'payment_method'     => 'offline',
                'payment_status'     => 'pending',
                'bank_account_id'    => $request->bank_account_id,
                'payment_proof'      => $proofPath,
                'coupon_id'          => $coupon?->id,
                'coupon_code'        => $coupon?->code,
                'discount_amount'    => $discountAmount,
            ]);

            TransactionItem::create([
                'transaction_id'        => $transaction->id,
                'product_id'            => $cart->product_id,
                'product_name_snapshot' => $cart->product->name,
                'quantity'              => 1,
                'unit_price'            => $subtotal,
                'subtotal'              => $subtotal,
            ]);

            // Increment coupon usage
            if ($coupon) {
                $coupon->incrementUsed();
            }

            $cart->delete();

            DB::commit();

            return redirect()->route('member.transactions.show', $transaction)
                ->with('success', 'Order placed! Please wait for payment verification (usually within 1–24 hours).');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}

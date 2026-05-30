<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BatchClass;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['batchClass.masterClass', 'batchClass.instructor', 'subscription'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('member.bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_class_id' => 'required|exists:batch_classes,id',
            'booking_type' => 'required|in:subscription,dropin',
            'subscription_id' => 'required_if:booking_type,subscription|exists:subscriptions,id',
        ]);

        $batchClass = BatchClass::findOrFail($request->batch_class_id);

        // Check if class is full
        if ($batchClass->remaining_slots <= 0) {
            return redirect()->back()->with('error', 'This class is fully booked!');
        }

        // Check if already booked
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('batch_class_id', $batchClass->id)
            ->whereIn('status', ['booked', 'completed'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'You have already booked this class!');
        }

        DB::beginTransaction();
        try {
            $booking = new Booking();
            $booking->user_id = Auth::id();
            $booking->batch_class_id = $batchClass->id;
            $booking->booking_type = $request->booking_type;
            $booking->status = 'booked';

            if ($request->booking_type == 'subscription') {
                $subscription = Subscription::findOrFail($request->subscription_id);
                
                // Check if subscription has quota
                if (!$subscription->hasQuotaRemaining()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Your subscription has no remaining quota!');
                }

                $booking->subscription_id = $subscription->id;
                $booking->price = 0; // Free with subscription
                
                // Use quota
                $subscription->useQuota();
            } else {
                $booking->price = $batchClass->price;
            }

            $booking->save();

            // Decrease remaining slots
            $batchClass->decrement('remaining_slots');

            DB::commit();

            return redirect()->route('member.bookings.index')
                ->with('success', 'Class booked successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id != Auth::id()) {
            abort(403);
        }

        if ($booking->status == 'cancelled') {
            return redirect()->back()->with('error', 'This booking is already cancelled!');
        }

        DB::beginTransaction();
        try {
            $booking->status = 'cancelled';
            $booking->save();

            // Restore remaining slots
            $booking->batchClass->increment('remaining_slots');

            // Restore subscription quota if applicable
            if ($booking->subscription_id) {
                $booking->subscription->decrement('quota_used');
            }

            DB::commit();

            return redirect()->back()->with('success', 'Booking cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Cancellation failed: ' . $e->getMessage());
        }
    }
}


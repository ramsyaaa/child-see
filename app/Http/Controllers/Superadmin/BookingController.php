<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BatchClass;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'batchClass.masterClass', 'batchClass.instructor', 'subscription']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereHas('batchClass', function($q) use ($request) {
                $q->whereDate('date', $request->date);
            });
        }

        // Filter by booking type
        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        // Search by member name
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('superadmin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'batchClass.masterClass', 'batchClass.instructor', 'batchClass.room', 'subscription']);
        
        return view('superadmin.bookings.show', compact('booking'));
    }

    public function create()
    {
        $members = User::where('role', 'MEMBER')->get();
        $batchClasses = BatchClass::with(['masterClass', 'instructor'])
            ->where('date', '>=', now())
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->where('remaining_slots', '>', 0)
            ->orderBy('date')
            ->get();

        return view('superadmin.bookings.create', compact('members', 'batchClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'batch_class_id' => 'required|exists:batch_classes,id',
            'booking_type' => 'required|in:subscription,dropin',
            'subscription_id' => 'required_if:booking_type,subscription|exists:subscriptions,id',
        ]);

        $batchClass = BatchClass::findOrFail($request->batch_class_id);

        // Check if class is full
        if ($batchClass->remaining_slots <= 0) {
            return redirect()->back()->with('error', 'This class is full!');
        }

        // Check for duplicate booking
        $existingBooking = Booking::where('user_id', $request->user_id)
            ->where('batch_class_id', $request->batch_class_id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'Member already has a booking for this class!');
        }

        DB::beginTransaction();
        try {
            $booking = new Booking();
            $booking->user_id = $request->user_id;
            $booking->batch_class_id = $request->batch_class_id;
            $booking->booking_type = $request->booking_type;
            $booking->status = 'booked';

            if ($request->booking_type == 'subscription') {
                $subscription = Subscription::findOrFail($request->subscription_id);
                
                if (!$subscription->hasQuotaRemaining()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Subscription has no remaining quota!');
                }

                $booking->subscription_id = $subscription->id;
                $booking->price = 0;
                $subscription->useQuota();
            } else {
                $booking->price = $batchClass->price;
            }

            $booking->save();
            $batchClass->decrement('remaining_slots');

            DB::commit();

            return redirect()->route('superadmin.bookings.index')
                ->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function destroy(Booking $booking)
    {
        DB::beginTransaction();
        try {
            // Restore slots and quota
            $booking->batchClass->increment('remaining_slots');
            
            if ($booking->subscription_id) {
                $booking->subscription->decrement('quota_used');
            }

            $booking->delete();

            DB::commit();

            return redirect()->route('superadmin.bookings.index')
                ->with('success', 'Booking deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }
}


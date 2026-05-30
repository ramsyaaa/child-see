<?php

namespace App\Http\Controllers\AdminNew;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BatchClass;
use Illuminate\Http\Request;

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

        return view('admin-new.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'batchClass.masterClass', 'batchClass.instructor', 'batchClass.room', 'subscription']);
        
        return view('admin-new.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:booked,cancelled,completed',
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;
        $booking->save();

        // If cancelling, restore slots and quota
        if ($request->status == 'cancelled' && $oldStatus != 'cancelled') {
            $booking->batchClass->increment('remaining_slots');
            
            if ($booking->subscription_id) {
                $booking->subscription->decrement('quota_used');
            }
        }

        return redirect()->route('admin-new.bookings.show', $booking)
            ->with('success', 'Booking status updated successfully!');
    }
}


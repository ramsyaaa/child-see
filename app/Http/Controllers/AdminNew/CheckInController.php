<?php

namespace App\Http\Controllers\AdminNew;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BatchClass;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        // Get today's classes by default
        $date = $request->filled('date') ? $request->date : now()->toDateString();
        
        $batchClasses = BatchClass::with(['masterClass', 'instructor', 'room'])
            ->whereDate('date', $date)
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        return view('admin-new.check-in.index', compact('batchClasses', 'date'));
    }

    public function show(BatchClass $batchClass)
    {
        $batchClass->load(['masterClass', 'instructor', 'room']);
        
        $bookings = Booking::with(['user', 'subscription'])
            ->where('batch_class_id', $batchClass->id)
            ->whereIn('status', ['booked', 'completed'])
            ->get();

        return view('admin-new.check-in.show', compact('batchClass', 'bookings'));
    }

    public function checkIn(Request $request, Booking $booking)
    {
        if ($booking->status != 'booked') {
            return redirect()->back()->with('error', 'Only booked (not yet checked in) bookings can be checked in!');
        }

        $booking->status = 'completed';
        $booking->checked_in_at = now();
        $booking->save();

        return redirect()->back()->with('success', 'Member checked in successfully!');
    }

    public function manualCheckIn(Request $request, BatchClass $batchClass)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if user already has a booking for this class
        $existingBooking = Booking::where('batch_class_id', $batchClass->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingBooking) {
            if ($existingBooking->status == 'booked') {
                $existingBooking->status = 'completed';
                $existingBooking->checked_in_at = now();
                $existingBooking->save();

                return redirect()->back()->with('success', 'Member checked in successfully!');
            } else {
                return redirect()->back()->with('error', 'Booking status is "' . $existingBooking->status . '" — cannot check in!');
            }
        }

        return redirect()->back()->with('error', 'No booking found for this member!');
    }
}


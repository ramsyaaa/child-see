<?php

namespace App\Http\Controllers\AdminNew;

use App\Http\Controllers\Controller;
use App\Models\BatchClass;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics for today
        $todayClasses = BatchClass::whereDate('date', Carbon::today())->count();
        $todayBookings = Booking::whereHas('batchClass', function($query) {
            $query->whereDate('date', Carbon::today());
        })->count();
        
        // Get pending payment verifications
        $pendingPayments = Transaction::where('payment_status', 'pending')->count();

        // Get today's schedule
        $todaySchedule = BatchClass::with(['masterClass', 'instructor', 'room'])
            ->whereDate('date', Carbon::today())
            ->orderBy('start_time')
            ->get();

        return view('admin_new.dashboard', compact(
            'todayClasses',
            'todayBookings',
            'pendingPayments',
            'todaySchedule'
        ));
    }
}


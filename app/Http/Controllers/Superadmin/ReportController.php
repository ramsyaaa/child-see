<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Subscription;
use App\Models\BatchClass;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        // Revenue by payment status
        $revenueData = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_status', DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_status')
            ->get();

        // Revenue by payment method
        $revenueByMethod = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Daily revenue trend
        $dailyRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $pendingRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'pending')
            ->sum('total_amount');

        return view('superadmin.reports.revenue', compact(
            'revenueData',
            'revenueByMethod',
            'dailyRevenue',
            'totalRevenue',
            'pendingRevenue',
            'startDate',
            'endDate',
            'period'
        ));
    }

    public function attendance(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        // Attendance by class
        $attendanceByClass = Booking::with('batchClass.masterClass')
            ->whereHas('batchClass', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->where('status', 'completed')
            ->select('batch_class_id', DB::raw('COUNT(*) as total'))
            ->groupBy('batch_class_id')
            ->get();

        // Attendance by instructor
        $attendanceByInstructor = Booking::with('batchClass.instructor')
            ->whereHas('batchClass', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->where('status', 'completed')
            ->join('batch_classes', 'bookings.batch_class_id', '=', 'batch_classes.id')
            ->select('batch_classes.instructor_id', DB::raw('COUNT(*) as total'))
            ->groupBy('batch_classes.instructor_id')
            ->get();

        // Total bookings vs completed
        $totalBookings = Booking::whereHas('batchClass', function($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        })->count();

        $completedBookings = Booking::whereHas('batchClass', function($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        })->where('status', 'completed')->count();

        $cancelledBookings = Booking::whereHas('batchClass', function($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        })->where('status', 'cancelled')->count();

        return view('superadmin.reports.attendance', compact(
            'attendanceByClass',
            'attendanceByInstructor',
            'totalBookings',
            'completedBookings',
            'cancelledBookings',
            'startDate',
            'endDate'
        ));
    }

    public function subscriptions(Request $request)
    {
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();

        // Expiring soon (within 7 days)
        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->with(['user', 'product'])
            ->get();

        // Subscription by product
        $subscriptionsByProduct = Subscription::with('product')
            ->select('product_id', DB::raw('COUNT(*) as total'))
            ->groupBy('product_id')
            ->get();

        return view('superadmin.reports.subscriptions', compact(
            'activeSubscriptions',
            'expiredSubscriptions',
            'cancelledSubscriptions',
            'expiringSoon',
            'subscriptionsByProduct'
        ));
    }

    public function index()
    {
        // Quick statistics for the dashboard
        $stats = [
            'total_revenue' => 'Rp ' . number_format(
                Transaction::where('payment_status', 'verified')
                    ->whereMonth('created_at', now()->month)
                    ->sum('total_amount'),
                0, ',', '.'
            ),
            'total_bookings' => Booking::whereMonth('created_at', now()->month)->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_members' => \App\Models\User::where('role', 'member')->count(),
        ];

        // Recent transactions (last 5)
        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming classes (next 5)
        $upcomingClasses = BatchClass::with(['masterClass', 'instructor'])
            ->where('date', '>=', now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->withCount('bookings')
            ->get();

        return view('superadmin.reports.index', compact('stats', 'recentTransactions', 'upcomingClasses'));
    }
}


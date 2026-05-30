<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BatchClass;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $batchClasses = BatchClass::with(['masterClass', 'instructor', 'room'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        // Group by date for easier display
        $classesByDate = $batchClasses->groupBy(function($class) {
            return $class->date->format('Y-m-d');
        });
        
        return view('superadmin.calendar.index', compact('classesByDate', 'startDate', 'endDate', 'month', 'year'));
    }
}


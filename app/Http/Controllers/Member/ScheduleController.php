<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\BatchClass;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = BatchClass::with(['masterClass', 'instructor', 'room'])
            ->where('date', '>=', now())
            ->where('status', 'active')
            ->where('visibility', 'public');

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by master class
        if ($request->filled('master_class_id')) {
            $query->where('master_class_id', $request->master_class_id);
        }

        // Filter by instructor
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $batchClasses = $query->orderBy('date')
            ->orderBy('start_time')
            ->paginate(15);

        // Get filter options
        $masterClasses = \App\Models\MasterClass::where('is_active', true)->get();
        $instructors = \App\Models\Instructor::where('is_active', true)->get();

        return view('member.schedule.index', compact('batchClasses', 'masterClasses', 'instructors'));
    }
}


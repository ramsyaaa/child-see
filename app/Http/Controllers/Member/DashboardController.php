<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $child = Child::where('user_id', $user->id)->first();

        $totalAssessments = Assessment::where('user_id', $user->id)->where('status', 'completed')->count();
        $recentAssessments = Assessment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['child', 'category'])
            ->latest()->take(5)->get();

        return view('member.dashboard', compact('child', 'totalAssessments', 'recentAssessments'));
    }
}

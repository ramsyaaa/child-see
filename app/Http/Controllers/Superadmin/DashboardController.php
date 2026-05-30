<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Category;
use App\Models\Child;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers     = User::where('role', 'member')->count();
        $totalChildren  = Child::count();
        $totalAssessments = Assessment::count();
        $completedAssessments = Assessment::where('status', 'completed')->count();
        $thisMonth      = Assessment::where('status','completed')
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)->count();
        $highIndications = Assessment::where('severity_level','heavy')->count();

        $byCategory = Assessment::where('status','completed')
            ->join('categories','assessments.category_id','=','categories.id')
            ->selectRaw('categories.name, count(*) as total')
            ->groupBy('categories.name')->pluck('total','categories.name');

        $bySeverity = Assessment::where('status','completed')
            ->selectRaw('severity_level, count(*) as total')
            ->groupBy('severity_level')->pluck('total','severity_level');

        $recentAssessments = Assessment::with(['child','category','user'])
            ->where('status','completed')
            ->latest()->take(8)->get();

        $categories = Category::withCount(['assessments' => fn($q) => $q->where('status','completed')])->get();

        return view('superadmin.dashboard', compact(
            'totalUsers','totalChildren','totalAssessments','completedAssessments',
            'thisMonth','highIndications','byCategory','bySeverity',
            'recentAssessments','categories'
        ));
    }
}
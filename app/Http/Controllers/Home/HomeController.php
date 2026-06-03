<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LandingFact;
use App\Models\LandingTeamMember;
use App\Models\LandingHki;
use App\Models\LandingPartner;
use App\Models\SiteSettings;

class HomeController extends Controller
{
    public function index()
    {
        $site        = SiteSettings::all_as_array();
        $categories  = Category::where('is_active', true)->orderBy('sort_order')->get();
        $facts       = LandingFact::where('is_active', true)->orderBy('sort_order')->get();
        $teamMembers = LandingTeamMember::where('is_active', true)->orderBy('sort_order')->get();
        $hkis        = LandingHki::where('is_active', true)->latest()->get();
        $partners    = LandingPartner::where('is_active', true)->orderBy('sort_order')->get();

        return view('home.index', compact('site', 'categories', 'facts', 'teamMembers', 'hkis', 'partners'));
    }

    public function about()
    {
        $site = SiteSettings::all_as_array();
        return view('home.about', compact('site'));
    }
}

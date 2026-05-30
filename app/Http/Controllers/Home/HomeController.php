<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SiteSettings;

class HomeController extends Controller
{
    public function index()
    {
        $site       = SiteSettings::all_as_array();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('home.index', compact('site', 'categories'));
    }

    public function about()
    {
        $site = SiteSettings::all_as_array();
        return view('home.about', compact('site'));
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSettings::all_as_array();
        return view('superadmin.inklu.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'site_name'       => 'required|string|max:80',
            'site_tagline'    => 'nullable|string|max:120',
            'site_email'      => 'nullable|email|max:120',
            'site_phone'      => 'nullable|string|max:30',
            'site_address'    => 'nullable|string|max:200',
            'site_instagram'  => 'nullable|url|max:200',
            'site_facebook'   => 'nullable|url|max:200',
            'site_whatsapp'   => 'nullable|string|max:200',
            'site_youtube'    => 'nullable|url|max:200',
            'seo_title'       => 'nullable|string|max:120',
            'seo_description' => 'nullable|string|max:320',
            'seo_keywords'    => 'nullable|string|max:300',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo') && $request->file('site_logo')->isValid()) {
            $path = $request->file('site_logo')->store('logos', 'public');
            $fields['site_logo'] = $path;
        }

        // Handle OG image upload
        if ($request->hasFile('og_image') && $request->file('og_image')->isValid()) {
            $path = $request->file('og_image')->store('og', 'public');
            $fields['og_image'] = $path;
        }

        foreach ($fields as $key => $value) {
            SiteSettings::set($key, $value ?? '');
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct(private ImageCompressionService $imageCompression)
    {
    }

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
            'site_logo'       => 'nullable|image|max:5120',
            'og_image'        => 'nullable|image|max:5120',
        ]);

        // Handle logo upload — compressed to ~500KB for fast loading
        if ($request->hasFile('site_logo') && $request->file('site_logo')->isValid()) {
            $old = SiteSettings::get('site_logo');
            $fields['site_logo'] = $this->imageCompression->storeCompressed($request->file('site_logo'), 'logos', 500);
            if ($old) Storage::disk('public')->delete($old);
        }

        // Handle OG image upload — compressed to ~500KB for fast loading
        if ($request->hasFile('og_image') && $request->file('og_image')->isValid()) {
            $old = SiteSettings::get('og_image');
            $fields['og_image'] = $this->imageCompression->storeCompressed($request->file('og_image'), 'og', 500);
            if ($old) Storage::disk('public')->delete($old);
        }

        foreach ($fields as $key => $value) {
            SiteSettings::set($key, $value ?? '');
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}

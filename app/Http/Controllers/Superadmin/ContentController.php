<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\CmsSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /** List all CMS pages */
    public function index()
    {
        $pages = CmsPage::withCount('sections')->get();
        return view('superadmin.content.index', compact('pages'));
    }

    /** Show all sections for a page + SEO settings */
    public function show(CmsPage $cmsPage)
    {
        $cmsPage->load('sections');
        return view('superadmin.content.show', compact('cmsPage'));
    }

    /** Save SEO fields for a page */
    public function updateSeo(Request $request, CmsPage $cmsPage)
    {
        $data = $request->validate([
            'seo_title'       => 'nullable|string|max:160',
            'seo_description' => 'nullable|string|max:320',
            'seo_keywords'    => 'nullable|string|max:320',
            'og_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('og_image')) {
            if ($cmsPage->og_image) {
                Storage::disk('public')->delete($cmsPage->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('cms/og', 'public');
        }

        $cmsPage->update($data);

        return back()->with('success', 'SEO settings saved.');
    }

    /** Edit a single section */
    public function editSection(CmsPage $cmsPage, CmsSection $section)
    {
        return view('superadmin.content.edit-section', compact('cmsPage', 'section'));
    }

    /** Save a section's content fields */
    public function updateSection(Request $request, CmsPage $cmsPage, CmsSection $section)
    {
        $incoming = $request->except(['_token', '_method']);
        $content  = $section->content ?? [];

        // Collect URL overrides first (keyed as {field}_url_override)
        $urlOverrides = [];
        foreach ($incoming as $key => $value) {
            if (str_ends_with($key, '_url_override')) {
                $realKey = substr($key, 0, -strlen('_url_override'));
                $urlOverrides[$realKey] = $value;
            }
        }

        foreach ($incoming as $key => $value) {
            // Skip the url_override meta-fields — handled separately
            if (str_ends_with($key, '_url_override')) continue;

            $isImageField = str_contains($key,'image') || str_contains($key,'img') || str_contains($key,'photo');

            if ($isImageField && $request->hasFile($key)) {
                // Uploaded file takes priority
                if (!empty($content[$key]) && str_starts_with($content[$key], 'cms/')) {
                    Storage::disk('public')->delete($content[$key]);
                }
                $content[$key] = $request->file($key)->store('cms/sections', 'public');
            } elseif ($isImageField && !empty($urlOverrides[$key])) {
                // URL override (no upload but URL provided)
                if (!empty($content[$key]) && str_starts_with($content[$key], 'cms/')) {
                    Storage::disk('public')->delete($content[$key]);
                }
                $content[$key] = $urlOverrides[$key];
            } elseif (!$isImageField) {
                // Plain text / heading / body fields
                $content[$key] = $value;
            }
            // If image field with no upload and no URL, leave the current value as-is
        }

        $section->update(['content' => $content]);

        return redirect()
            ->route('superadmin.content.show', $cmsPage)
            ->with('success', 'Section "' . $section->label . '" updated.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsPage extends Model
{
    use HasFactory;

    protected $table = 'cms_pages';

    protected $fillable = [
        'slug', 'title', 'seo_title', 'seo_description', 'seo_keywords', 'og_image',
    ];

    public function sections()
    {
        return $this->hasMany(CmsSection::class)->orderBy('sort_order');
    }

    public function section(string $key): ?CmsSection
    {
        return $this->sections->firstWhere('section_key', $key);
    }

    /**
     * Get content value for a section + field, with optional default.
     */
    public function get(string $sectionKey, string $field, mixed $default = null): mixed
    {
        $section = $this->section($sectionKey);
        if (!$section) return $default;
        return $section->content[$field] ?? $default;
    }

    /**
     * Return the page by slug, eager-loading sections. Null if not seeded yet.
     */
    public static function forSlug(string $slug): ?self
    {
        return static::with('sections')->where('slug', $slug)->first();
    }
}

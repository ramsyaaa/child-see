<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsSection extends Model
{
    use HasFactory;

    protected $table = 'cms_sections';

    protected $fillable = [
        'cms_page_id', 'section_key', 'label', 'content', 'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    /**
     * Get a single field value from this section's content.
     */
    public function get(string $field, mixed $default = null): mixed
    {
        return $this->content[$field] ?? $default;
    }
}

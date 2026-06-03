<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPartner extends Model
{
    use HasFactory;
    protected $table = 'landing_partners';
    protected $fillable = ['name', 'logo', 'website_url', 'description', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getLogoUrlAttribute(): ?string {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}

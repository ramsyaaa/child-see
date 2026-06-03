<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingTeamMember extends Model
{
    use HasFactory;
    protected $table = 'landing_team_members';
    protected $fillable = ['name', 'role_label', 'affiliation', 'group', 'photo', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getPhotoUrlAttribute(): ?string {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}

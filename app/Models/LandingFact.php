<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingFact extends Model
{
    use HasFactory;
    protected $table = 'landing_facts';
    protected $fillable = ['title', 'body', 'icon', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function scopeActive($q) { return $q->where('is_active', true); }
}

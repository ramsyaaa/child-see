<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentificationResult extends Model
{
    protected $fillable = [
        'user_id', 'type', 'answers', 'score', 'level',
        'child_name', 'child_dob', 'child_age', 'filler_name', 'filler_status',
    ];

    protected $casts = ['answers' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisabilityNameAttribute(): string
    {
        return match($this->type) {
            'penglihatan' => 'Hambatan Penglihatan',
            'pendengaran' => 'Hambatan Pendengaran',
            'intelektual' => 'Hambatan Intelektual',
            default => ucfirst($this->type),
        };
    }

    public function getDisabilityColorAttribute(): string
    {
        return match($this->type) {
            'penglihatan' => '#1E3A5F',
            'pendengaran' => '#8D77AB',
            'intelektual' => '#A86916',
            default => '#5C477F',
        };
    }

    public function getLevelLabelAttribute(): string
    {
        return match($this->level) {
            'low'  => 'Indikasi Rendah',
            'mid'  => 'Indikasi Sedang',
            'high' => 'Indikasi Tinggi',
            default => ucfirst($this->level),
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'specialization',
        'photo_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all batch classes for this instructor
     */
    public function batchClasses()
    {
        return $this->hasMany(BatchClass::class);
    }

    /**
     * Scope to get only active instructors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}


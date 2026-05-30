<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'difficulty_level',
        'default_duration',
        'is_active',
        'color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_duration' => 'integer',
    ];

    /**
     * Get all batch classes for this master class
     */
    public function batchClasses()
    {
        return $this->hasMany(BatchClass::class);
    }

    /**
     * Scope to get only active classes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}


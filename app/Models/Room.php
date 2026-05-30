<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'capacity',
        'facilities',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Get all batch classes for this room
     */
    public function batchClasses()
    {
        return $this->hasMany(BatchClass::class);
    }

    /**
     * Scope to get only active rooms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}


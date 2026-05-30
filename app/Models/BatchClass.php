<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_class_id',
        'instructor_id',
        'room_id',
        'date',
        'start_time',
        'end_time',
        'price',
        'capacity',
        'remaining_slots',
        'status',
        'visibility',
        'is_have_gender_restriction',
        'gender_restriction',
        'is_have_age_restriction',
        'age_restriction',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'remaining_slots' => 'integer',
        'is_have_gender_restriction' => 'boolean',
        'is_have_age_restriction' => 'boolean',
    ];

    /**
     * Get the master class
     */
    public function masterClass()
    {
        return $this->belongsTo(MasterClass::class);
    }

    /**
     * Get the instructor
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get all bookings for this batch class
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if class is full
     */
    public function isFull()
    {
        return $this->remaining_slots <= 0;
    }

    /**
     * Scope to get only active classes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only public classes
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope to get upcoming classes
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
                     ->orderBy('date')
                     ->orderBy('start_time');
    }
}


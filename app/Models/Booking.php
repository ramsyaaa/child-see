<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'batch_class_id',
        'subscription_id',
        'booking_type',
        'price',
        'status',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the batch class
     */
    public function batchClass()
    {
        return $this->belongsTo(BatchClass::class);
    }

    /**
     * Get the subscription (if used)
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if booking is checked in
     */
    public function isCheckedIn()
    {
        return $this->checked_in_at !== null;
    }

    /**
     * Check-in the booking
     */
    public function checkIn()
    {
        $this->update([
            'checked_in_at' => now(),
            'status' => 'completed',
        ]);
    }

    /**
     * Scope to get only active (booked) bookings
     * Note: the DB enum uses 'booked', not 'confirmed'
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope to get only booked bookings
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope to get only completed bookings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}


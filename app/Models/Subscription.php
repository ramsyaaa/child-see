<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'start_date',
        'end_date',
        'quota_allocated',
        'quota_used',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'quota_allocated' => 'integer',
        'quota_used' => 'integer',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all bookings using this subscription
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= now()->toDateString();
    }

    /**
     * Check if subscription has quota remaining
     */
    public function hasQuotaRemaining()
    {
        if ($this->quota_allocated === null) {
            return true; // Unlimited
        }
        return $this->quota_used < $this->quota_allocated;
    }

    /**
     * Get remaining quota
     */
    public function getRemainingQuota()
    {
        if ($this->quota_allocated === null) {
            return 'Unlimited';
        }
        return max(0, $this->quota_allocated - $this->quota_used);
    }

    /**
     * Use quota
     */
    public function useQuota($amount = 1)
    {
        $this->increment('quota_used', $amount);
    }

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '>=', now()->toDateString());
    }
}


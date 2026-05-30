<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description', 'type', 'value',
        'min_purchase', 'max_uses', 'used_count',
        'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'is_active'    => 'boolean',
        'value'        => 'decimal:2',
        'min_purchase' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check whether this coupon is currently usable for a given subtotal.
     * Returns true/false without throwing.
     */
    public function isValid(float $subtotal = 0): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        if ($this->min_purchase !== null && $subtotal < $this->min_purchase) return false;
        return true;
    }

    /**
     * Calculate the discount amount (never exceeds subtotal).
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
        } else {
            $discount = (float) $this->value;
        }
        return min($discount, $subtotal);
    }

    /** Increment used_count by 1. */
    public function incrementUsed(): void
    {
        $this->increment('used_count');
    }

    /** Scope: only active, non-expired, within use limit. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')->orWhereColumn('used_count', '<', 'max_uses');
            });
    }
}

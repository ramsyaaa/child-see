<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'status',
    ];

    protected $casts = [
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
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get only pending carts
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get abandoned carts
     */
    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }

    /**
     * Mark cart as abandoned
     */
    public function markAsAbandoned($reason = null)
    {
        $this->update(['status' => 'abandoned']);
        
        // Create abandoned cart record
        AbandonedCart::create([
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'reason' => $reason,
            'captured_at' => now(),
        ]);
    }
}


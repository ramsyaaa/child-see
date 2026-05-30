<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'duration_days',
        'quota',
        'quota_type',
        'allowed_class_categories',
        'excluded_classes',
        'is_active',
        'is_popular',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'quota' => 'integer',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'allowed_class_categories' => 'array',
        'excluded_classes' => 'array',
    ];

    /**
     * Get all subscriptions for this product
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all transaction items for this product
     */
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get products by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if product is a subscription
     */
    public function isSubscription()
    {
        return $this->type === 'subscription';
    }

    /**
     * Check if product is a drop-in
     */
    public function isDropIn()
    {
        return $this->type === 'dropin';
    }

    /**
     * Check if product is a bundle
     */
    public function isBundle()
    {
        return $this->type === 'bundle';
    }
}


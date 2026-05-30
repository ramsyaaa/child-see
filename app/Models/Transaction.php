<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_number',
        'total_amount',
        'payment_method',
        'bank_account_id',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'payment_status',
        'payment_proof',
        'verified_by',
        'verified_at',
        'verification_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'verified_at'     => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bank account (for offline payments)
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Get the coupon applied to this transaction
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the verifier (admin/superadmin)
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get all transaction items
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Scope to get pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope to get verified transactions
     */
    public function scopeVerified($query)
    {
        return $query->where('payment_status', 'verified');
    }

    /**
     * Verify the transaction
     */
    public function verify($verifierId, $notes = null)
    {
        $this->update([
            'payment_status' => 'verified',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Reject the transaction
     */
    public function reject($verifierId, $notes = null)
    {
        $this->update([
            'payment_status' => 'rejected',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'va_id',
        'user_id',
        'amount',
        'proof_file',
        'verified_by',
        'verified_at',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'verified_at' => 'datetime'
    ];

    public $timestamps = false;

    /**
     * Get the virtual account for this payment.
     */
    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class, 'va_id');
    }

    /**
     * Get the user who made this payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who verified this payment.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the refund for this payment.
     */
    public function refund()
    {
        return $this->hasOne(Refund::class, 'payment_id');
    }
}

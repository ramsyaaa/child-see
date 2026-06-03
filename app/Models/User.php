<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'username',
        'email',
        'password',
        'password_hash',
        'phone',
        'phone_number',
        'role',
        'status',
        'rejection_reason',
        'nik',
        'ktp_photo',
        'npwp',
        'organization_name',
        'organization_type',
        'child_quota',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
            'password_hash' => 'hashed',
        ];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the auction lots created by this admin user.
     */
    public function auctionLots()
    {
        return $this->hasMany(AuctionLot::class, 'admin_id');
    }

    /**
     * Get the bids placed by this user.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'bidder_id');
    }

    /**
     * Get the payments made by this user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    /**
     * Get the auction wins for this user.
     */
    public function auctionWins()
    {
        return $this->hasMany(AuctionWinner::class, 'user_id');
    }

    /**
     * Get the notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Get the OTP codes for this user.
     */
    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class, 'user_id');
    }

    /**
     * Get the latest OTP code for this user.
     */
    public function latestOtpCode()
    {
        return $this->hasOne(OtpCode::class, 'user_id')->latest();
    }

    /**
     * Get the bank accounts for this user.
     */
    public function bankAccounts()
    {
        return $this->hasMany(UserBankAccount::class, 'user_id');
    }

    /**
     * Get the primary bank account for this user.
     */
    public function primaryBankAccount()
    {
        return $this->hasOne(UserBankAccount::class, 'user_id')->where('is_primary', true);
    }

    /**
     * Get the full URL for the KTP photo.
     */
    public function getKtpPhotoUrlAttribute()
    {
        if ($this->ktp_photo) {
            return asset('storage/' . $this->ktp_photo);
        }
        return null;
    }

    /**
     * Check if user has completed bidder profile.
     */
    public function hasCompleteBidderProfile()
    {
        return $this->role === 'BIDDER' &&
               !empty($this->nik) &&
               !empty($this->ktp_photo) &&
               $this->status === 'ACTIVE';
    }

    /**
     * Check if user has uploaded required documents
     */
    public function hasUploadedDocuments()
    {
        return !empty($this->nik) && !empty($this->ktp_photo);
    }

    /**
     * Check if user is ready for verification (has uploaded documents)
     */
    public function isReadyForVerification()
    {
        return $this->hasUploadedDocuments() && $this->status === 'PENDING';
    }

    /**
     * Check if verification data is locked (user is active or rejected)
     */
    public function isVerificationDataLocked()
    {
        return $this->status === 'ACTIVE';
    }

    /**
     * Check if user status is pending
     */
    public function isPending()
    {
        return $this->status === 'PENDING';
    }

    /**
     * Check if user status is active
     */
    public function isActive()
    {
        return $this->status === 'ACTIVE';
    }

    /**
     * Check if user status is rejected
     */
    public function isRejected()
    {
        return $this->status === 'REJECTED';
    }

    /**
     * Check if user status is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'SUSPENDED';
    }

    /**
     * Check if user status is verifying
     */
    public function isVerifying()
    {
        return $this->status === 'VERIFYING';
    }

    /**
     * Check if user status is OTP (pending email verification)
     */
    public function isOtp()
    {
        return $this->status === 'OTP';
    }

    /**
     * Check if user can edit verification fields
     */
    public function canEditVerificationFields()
    {
        // Can edit if status is PENDING or REJECTED (for re-upload)
        return $this->status === 'PENDING' || $this->status === 'REJECTED';
    }

    /**
     * Check if user can bid (only ACTIVE users can bid)
     */
    public function canBid(): bool
    {
        return $this->status === 'ACTIVE';
    }

    /**
     * Get user status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'OTP' => 'Menunggu Verifikasi Email',
            'PENDING' => 'Menunggu Verifikasi Admin',
            'VERIFYING' => 'Dalam Proses Verifikasi',
            'ACTIVE' => 'Aktif',
            'REJECTED' => 'Ditolak',
            'SUSPENDED' => 'Dinonaktifkan',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get formatted NIK for display.
     */
    public function getFormattedNikAttribute()
    {
        if ($this->nik) {
            return substr($this->nik, 0, 4) . '-' .
                   substr($this->nik, 4, 4) . '-' .
                   substr($this->nik, 8, 4) . '-' .
                   substr($this->nik, 12, 4);
        }
        return null;
    }

    // ========== Fitness Studio Relationships ==========

    /**
     * Get all subscriptions for this user
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active subscriptions
     */
    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class)
                    ->where('status', 'active')
                    ->where('end_date', '>=', now()->toDateString());
    }

    /**
     * Get all bookings for this user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all transactions for this user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the cart for this user
     */
    public function cart()
    {
        return $this->hasOne(Cart::class)->where('status', 'pending');
    }

    /**
     * Check if user is superadmin (case-insensitive)
     */
    public function isSuperadmin()
    {
        return strtolower($this->role) === 'superadmin';
    }

    /**
     * Check if user is admin (case-insensitive)
     */
    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    /**
     * Check if user is member (case-insensitive)
     */
    public function isMember()
    {
        return strtolower($this->role) === 'member';
    }

    public function isOrganization()
    {
        return strtolower($this->role) === 'organization';
    }

    public function getRoleLabel(): string
    {
        return match(strtoupper($this->role)) {
            'SUPERADMIN'   => 'Superadmin',
            'ADMIN'        => 'Admin',
            'MEMBER'       => 'Orang Tua / Guru',
            'ORGANIZATION' => 'Organisasi / Sekolah',
            default        => $this->role,
        };
    }

    /**
     * Get the password attribute (for compatibility with password_hash column)
     */
    public function getPasswordAttribute()
    {
        return $this->attributes['password'] ?? $this->attributes['password_hash'] ?? null;
    }

    /**
     * Set the password attribute (for compatibility)
     */
    public function setPasswordAttribute($value)
    {
        if (Schema::hasColumn('users', 'password')) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password_hash'] = $value;
        }
    }

    public function identificationResults()
    {
        return $this->hasMany(\App\Models\IdentificationResult::class);
    }

    public function children()
    {
        return $this->hasMany(\App\Models\Child::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingHki extends Model
{
    use HasFactory;
    protected $table = 'landing_hki';
    protected $fillable = ['title', 'description', 'image', 'certificate_number', 'issued_date', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'issued_date' => 'date'];
    public function getImageUrlAttribute(): ?string {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}

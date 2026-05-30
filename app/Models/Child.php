<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Child extends Model {
    protected $fillable = ['user_id','full_name','nick_name','gender','birth_place','birth_date','parent_name','parent_phone','school_name','class_name','address','photo','notes'];
    protected $casts = ['birth_date' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function assessments(): HasMany { return $this->hasMany(Assessment::class); }

    public function getAgeAttribute(): int {
        return Carbon::parse($this->birth_date)->age;
    }
    public function getPhotoUrlAttribute(): ?string {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
    public function getGenderLabelAttribute(): string {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }
}

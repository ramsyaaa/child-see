<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Assessment extends Model {
    protected $fillable = ['assessment_code','user_id','child_id','category_id','assessment_date','total_score','result_label','result_description','recommendation','severity_level','color','status'];
    protected $casts = ['assessment_date' => 'datetime', 'total_score' => 'float'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function child(): BelongsTo { return $this->belongsTo(Child::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function answers(): HasMany { return $this->hasMany(AssessmentAnswer::class); }
    public function domainScores(): HasMany { return $this->hasMany(AssessmentDomainScore::class); }

    public static function generateCode(): string {
        return 'CS-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public function getSeverityLabelAttribute(): string {
        return match($this->severity_level) {
            'normal' => 'Belum Terindikasi',
            'light'  => 'Terindikasi Ringan',
            'medium' => 'Terindikasi Sedang',
            'heavy'  => 'Terindikasi Kuat',
            default  => ucfirst($this->severity_level ?? '-'),
        };
    }

    public function getSeverityBadgeColorAttribute(): string {
        return match($this->severity_level) {
            'normal' => '#839986',
            'light'  => '#8D77AB',
            'medium' => '#A86916',
            'heavy'  => '#dc3545',
            default  => '#6c757d',
        };
    }
}

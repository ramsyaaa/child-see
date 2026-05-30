<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model {
    protected $fillable = ['category_id','name','description','opening_text','closing_text','sort_order','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function questionnaires(): HasMany { return $this->hasMany(Questionnaire::class)->orderBy('sort_order'); }
    public function assessmentRules(): HasMany { return $this->hasMany(AssessmentRule::class); }
    public function assessmentDomainScores(): HasMany { return $this->hasMany(AssessmentDomainScore::class); }

    public function scopeActive($query) { return $query->where('is_active', true)->orderBy('sort_order'); }
}

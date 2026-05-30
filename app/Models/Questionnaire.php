<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model {
    protected $fillable = ['category_id','domain_id','question_code','question','question_type','helper_text','weight','is_required','sort_order','is_active'];
    protected $casts = ['weight' => 'float', 'is_active' => 'boolean', 'is_required' => 'boolean'];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function domain(): BelongsTo { return $this->belongsTo(Domain::class); }
    public function answerOptions(): HasMany { return $this->hasMany(AnswerOption::class)->orderBy('sort_order'); }

    public function scopeActive($query) { return $query->where('is_active', true)->orderBy('sort_order'); }
}

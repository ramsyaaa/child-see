<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerOption extends Model {
    protected $fillable = ['questionnaire_id','label','value','score','sort_order'];
    protected $casts = ['score' => 'float'];

    public function questionnaire(): BelongsTo { return $this->belongsTo(Questionnaire::class); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}

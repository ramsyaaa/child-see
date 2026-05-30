<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentAnswer extends Model {
    protected $fillable = ['assessment_id','questionnaire_id','answer_option_id','answer_text','score'];
    protected $casts = ['score' => 'float'];

    public function assessment(): BelongsTo { return $this->belongsTo(Assessment::class); }
    public function questionnaire(): BelongsTo { return $this->belongsTo(Questionnaire::class); }
    public function answerOption(): BelongsTo { return $this->belongsTo(AnswerOption::class); }
}

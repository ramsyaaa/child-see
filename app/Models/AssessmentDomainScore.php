<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentDomainScore extends Model {
    protected $fillable = ['assessment_id','domain_id','total_score','result_label','result_description','severity_level','color'];
    protected $casts = ['total_score' => 'float'];

    public function assessment(): BelongsTo { return $this->belongsTo(Assessment::class); }
    public function domain(): BelongsTo { return $this->belongsTo(Domain::class); }
}

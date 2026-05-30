<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentRule extends Model {
    protected $fillable = ['category_id','domain_id','label','severity_level','min_score','max_score','description','recommendation','color'];
    protected $casts = ['min_score' => 'float', 'max_score' => 'float'];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function domain(): BelongsTo { return $this->belongsTo(Domain::class); }
}

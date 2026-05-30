<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {
    protected $fillable = ['name','slug','type','description','icon','color','sort_order','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function domains(): HasMany { return $this->hasMany(Domain::class)->orderBy('sort_order'); }
    public function questionnaires(): HasMany { return $this->hasMany(Questionnaire::class); }
    public function assessmentRules(): HasMany { return $this->hasMany(AssessmentRule::class); }
    public function assessments(): HasMany { return $this->hasMany(Assessment::class); }

    public function scopeActive($query) { return $query->where('is_active', true)->orderBy('sort_order'); }
}

<?php
namespace App\Services;
use App\Models\AssessmentRule;
use App\Models\Category;
use App\Models\Domain;

class RuleEngineService {
    public function classify(Category $category, float $totalScore): ?AssessmentRule {
        return AssessmentRule::where('category_id', $category->id)
            ->whereNull('domain_id')
            ->where('min_score', '<=', $totalScore)
            ->where('max_score', '>=', $totalScore)
            ->first();
    }

    public function classifyDomain(Domain $domain, float $domainScore): ?AssessmentRule {
        return AssessmentRule::where('category_id', $domain->category_id)
            ->where('domain_id', $domain->id)
            ->where('min_score', '<=', $domainScore)
            ->where('max_score', '>=', $domainScore)
            ->first();
    }
}

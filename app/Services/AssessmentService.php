<?php
namespace App\Services;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentDomainScore;
use App\Models\AnswerOption;
use App\Models\Category;
use App\Models\Child;
use App\Models\Domain;
use App\Models\User;

class AssessmentService {
    public function __construct(
        private ScoringService $scoring,
        private RuleEngineService $rules,
    ) {}

    public function start(User $user, Child $child, Category $category): Assessment {
        return Assessment::create([
            'assessment_code'  => Assessment::generateCode(),
            'user_id'          => $user->id,
            'child_id'         => $child->id,
            'category_id'      => $category->id,
            'assessment_date'  => now(),
            'status'           => 'draft',
        ]);
    }

    public function saveAnswers(Assessment $assessment, array $answers): void {
        // $answers: [questionnaire_id => answer_option_id]
        $assessment->answers()->delete();
        foreach ($answers as $questionnaireId => $optionId) {
            $option = AnswerOption::find($optionId);
            if (!$option) continue;
            AssessmentAnswer::create([
                'assessment_id'    => $assessment->id,
                'questionnaire_id' => $questionnaireId,
                'answer_option_id' => $optionId,
                'score'            => $option->score,
            ]);
        }
    }

    public function finalize(Assessment $assessment): Assessment {
        $scores = $this->scoring->calculate($assessment);
        $assessment->update(['total_score' => $scores['total_score']]);

        // Domain scores
        foreach ($scores['domain_scores'] as $domainId => $domainScore) {
            $domain = Domain::find($domainId);
            if (!$domain) continue;
            $rule = $this->rules->classifyDomain($domain, $domainScore);
            AssessmentDomainScore::updateOrCreate(
                ['assessment_id' => $assessment->id, 'domain_id' => $domainId],
                ['total_score' => $domainScore, 'result_label' => $rule?->label, 'result_description' => $rule?->description, 'severity_level' => $rule?->severity_level, 'color' => $rule?->color]
            );
        }

        // Overall classification
        $category = $assessment->category;
        $rule = $this->rules->classify($category, $scores['total_score']);
        $assessment->update([
            'result_label'       => $rule?->label ?? 'Tidak Dapat Ditentukan',
            'result_description' => $rule?->description,
            'recommendation'     => $rule?->recommendation,
            'severity_level'     => $rule?->severity_level ?? 'normal',
            'color'              => $rule?->color ?? '#5C477F',
            'status'             => 'completed',
        ]);

        return $assessment->fresh(['child', 'category', 'domainScores.domain', 'answers.questionnaire']);
    }
}

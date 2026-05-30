<?php
namespace App\Services;
use App\Models\Assessment;

class ScoringService {
    public function calculate(Assessment $assessment): array {
        $assessment->load(['answers.questionnaire', 'answers.answerOption']);
        $totalScore = 0;
        $domainScores = [];
        foreach ($assessment->answers as $answer) {
            $questionScore = $answer->score * ($answer->questionnaire->weight ?? 1);
            $totalScore += $questionScore;
            $domainId = $answer->questionnaire->domain_id;
            $domainScores[$domainId] = ($domainScores[$domainId] ?? 0) + $questionScore;
        }
        return ['total_score' => round($totalScore, 2), 'domain_scores' => $domainScores];
    }
}

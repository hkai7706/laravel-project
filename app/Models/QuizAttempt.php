<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'difficulty',
        'total_score',
        'correct_answers',
        'incorrect_answers',
        'time_taken',
        'badge',
        'rank_title',
        'category_scores',
        'completed',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'category_scores' => 'array',
        'completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function calculateBadgeAndRank()
    {
        $score = $this->total_score;
        $maxScore = 100; // 10 questions * 10 marks = 100

        $percentage = ($score / $maxScore) * 100;

        // Badge assignment
        if ($percentage >= 90) {
            $this->badge = '💖 Perfect Love';
            $this->rank_title = 'Love Master';
        } elseif ($percentage >= 80) {
            $this->badge = '💕 True Romance';
            $this->rank_title = 'Romance Expert';
        } elseif ($percentage >= 70) {
            $this->badge = '💗 Sweetheart';
            $this->rank_title = 'Love Enthusiast';
        } elseif ($percentage >= 60) {
            $this->badge = '💓 Love Apprentice';
            $this->rank_title = 'Budding Romantic';
        } elseif ($percentage >= 50) {
            $this->badge = '💝 Cupid Trainee';
            $this->rank_title = 'Love Student';
        } else {
            $this->badge = '💔 Heartbreaker';
            $this->rank_title = 'Love Learner';
        }

        $this->save();
    }
}
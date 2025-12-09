<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UserAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'selected_answer',
        'is_correct',
        'time_taken',
    ];
    protected $casts = [
        'is_correct' => 'boolean',
        'time_taken' => 'integer',
    ];
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
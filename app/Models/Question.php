<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'category',
        'options',
        'correct_answer',
        'marks',
    ];
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'integer',
        'marks' => 'integer',
    ];
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
    public function isCorrectAnswer($answerIndex)
    {
        return $this->correct_answer === $answerIndex;
    }
}
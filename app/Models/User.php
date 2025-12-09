<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'unique_id',
        'ip_address',
    ];
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->unique_id)) {
                $user->unique_id = Str::uuid();
            }
        });
    }
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
    public function latestQuizAttempt()
    {
        return $this->hasOne(QuizAttempt::class)->latestOfMany();
    }
    public function bestScore()
    {
        return $this->quizAttempts()
            ->where('completed', true)
            ->max('total_score') ?? 0;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index()
    {
        // Check if user already has active session
        $userId = Session::get('quiz_user_id');
        $attemptId = Session::get('quiz_attempt_id');
        
        $user = null;
        $attempt = null;
        
        if ($userId && $attemptId) {
            $user = User::find($userId);
            $attempt = QuizAttempt::find($attemptId);
        }
        
        // Get leaderboard
        $leaderboard = $this->getLeaderboard();
        
        return view('quiz.index', compact('user', 'attempt', 'leaderboard'));
    }

    public function startQuiz(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'difficulty' => 'required|in:Easy,Medium,Hard',
        ]);

        try {
            $ipAddress = $request->ip();
            
            // Check for existing user by IP (anti-cheat)
            $existingUser = User::where('ip_address', $ipAddress)
                ->whereHas('quizAttempts', function($query) {
                    $query->where('completed', true)
                        ->where('created_at', '>', now()->subHours(24));
                })
                ->first();

            if ($existingUser) {
                return response()->json([
                    'error' => 'You have already taken the quiz in the last 24 hours. Please try again later!'
                ], 403);
            }

            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'unique_id' => Str::uuid(),
                'ip_address' => $ipAddress,
            ]);

            // Create quiz attempt
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'difficulty' => $validated['difficulty'],
                'started_at' => now(),
            ]);

            // Store in session
            Session::put('quiz_user_id', $user->id);
            Session::put('quiz_attempt_id', $attempt->id);
            Session::put('quiz_start_time', time());

            // Get random 10 questions (CHANGED FROM 20)
            $questions = Question::inRandomOrder()->limit(10)->get();
            Session::put('quiz_questions', $questions->pluck('id')->toArray());

            return response()->json([
                'success' => true,
                'user' => $user,
                'attempt' => $attempt,
                'questions' => $questions->map(function($q) {
                    return [
                        'id' => $q->id,
                        'question' => $q->question,
                        'category' => $q->category,
                        'options' => $q->options,
                    ];
                }),
                'time_limit' => $this->getTimeLimit($validated['difficulty']),
            ]);

        } catch (\Exception $e) {
            \Log::error('Quiz Start Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while starting the quiz. Please try again.'
            ], 500);
        }
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|integer|min:-1|max:3',
            'time_taken' => 'nullable|integer',
        ]);

        $attemptId = Session::get('quiz_attempt_id');
        if (!$attemptId) {
            return response()->json(['error' => 'No active quiz attempt'], 400);
        }

        $question = Question::findOrFail($request->question_id);
        $isCorrect = $question->isCorrectAnswer($request->answer);

        // Save user answer
        UserAnswer::create([
            'quiz_attempt_id' => $attemptId,
            'question_id' => $question->id,
            'selected_answer' => $request->answer,
            'is_correct' => $isCorrect,
            'time_taken' => $request->time_taken,
        ]);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
        ]);
    }

    public function completeQuiz(Request $request)
    {
        $attemptId = Session::get('quiz_attempt_id');
        if (!$attemptId) {
            return response()->json(['error' => 'No active quiz attempt'], 400);
        }

        $attempt = QuizAttempt::with(['userAnswers.question'])->findOrFail($attemptId);
        
        // Calculate scores (CHANGED: 10 marks per question)
        $correctAnswers = $attempt->userAnswers->where('is_correct', true)->count();
        $incorrectAnswers = $attempt->userAnswers->where('is_correct', false)->count();
        $totalScore = $correctAnswers * 10; // Changed from 5 to 10

        // Calculate category-wise scores
        $categoryScores = [];
        foreach ($attempt->userAnswers as $answer) {
            $category = $answer->question->category;
            if (!isset($categoryScores[$category])) {
                $categoryScores[$category] = ['correct' => 0, 'total' => 0];
            }
            $categoryScores[$category]['total']++;
            if ($answer->is_correct) {
                $categoryScores[$category]['correct']++;
            }
        }

        // Calculate time taken
        $startTime = Session::get('quiz_start_time');
        $timeTaken = $startTime ? (time() - $startTime) : null;

        // Update attempt
        $attempt->update([
            'total_score' => $totalScore,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'time_taken' => $timeTaken,
            'category_scores' => $categoryScores,
            'completed' => true,
            'completed_at' => now(),
        ]);

        // Calculate badge and rank
        $attempt->calculateBadgeAndRank();

        // Clear session
        Session::forget(['quiz_user_id', 'quiz_attempt_id', 'quiz_questions', 'quiz_start_time']);

        // Get updated leaderboard
        $leaderboard = $this->getLeaderboard();

        return response()->json([
            'success' => true,
            'results' => [
                'total_score' => $totalScore,
                'correct_answers' => $correctAnswers,
                'incorrect_answers' => $incorrectAnswers,
                'time_taken' => $timeTaken,
                'category_scores' => $categoryScores,
                'badge' => $attempt->badge,
                'rank_title' => $attempt->rank_title,
            ],
            'leaderboard' => $leaderboard,
        ]);
    }

    public function getLeaderboard()
    {
        return QuizAttempt::with('user')
            ->where('completed', true)
            ->orderBy('total_score', 'desc')
            ->orderBy('time_taken', 'asc')
            ->limit(10)
            ->get()
            ->map(function($attempt, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $attempt->user->name,
                    'score' => $attempt->total_score,
                    'badge' => $attempt->badge,
                    'rank_title' => $attempt->rank_title,
                    'time_taken' => $this->formatTime($attempt->time_taken),
                    'difficulty' => $attempt->difficulty,
                ];
            });
    }

    private function getTimeLimit($difficulty)
    {
        return match($difficulty) {
            'Easy' => 60,
            'Medium' => 45,
            'Hard' => 30,
            default => 60,
        };
    }

    private function formatTime($seconds)
    {
        if (!$seconds) return 'N/A';
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
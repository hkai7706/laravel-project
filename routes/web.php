<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LoveSurveyController;

/*
|--------------------------------------------------------------------------
| Love Survey – FIRST PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', [LoveSurveyController::class, 'index'])->name('survey.index');

Route::post('/love-survey/submit', [LoveSurveyController::class, 'submitSurvey'])->name('survey.submit');
Route::get('/love-survey/complete', [LoveSurveyController::class, 'complete'])->name('survey.complete');


/*
|--------------------------------------------------------------------------
| Quiz System
|--------------------------------------------------------------------------
*/
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz/start', [QuizController::class, 'startQuiz'])->name('quiz.start');

Route::post('/quiz/answer', [QuizController::class, 'submitAnswer'])
    ->middleware('quiz.session')
    ->name('quiz.answer');

Route::post('/quiz/complete', [QuizController::class, 'completeQuiz'])
    ->middleware('quiz.session')
    ->name('quiz.complete');

Route::get('/quiz/leaderboard', [QuizController::class, 'getLeaderboard'])->name('quiz.leaderboard');

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\QuizController;




Route::get('/', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz/start', [QuizController::class, 'startQuiz'])->name('quiz.start');
Route::post('/quiz/answer', [QuizController::class, 'submitAnswer'])
    ->middleware('quiz.session')
    ->name('quiz.answer');
Route::post('/quiz/complete', [QuizController::class, 'completeQuiz'])
    ->middleware('quiz.session')
    ->name('quiz.complete');
Route::get('/quiz/leaderboard', [QuizController::class, 'getLeaderboard'])->name('quiz.leaderboard');


use App\Http\Controllers\LoveSurveyController;

Route::get('/love-survey', [LoveSurveyController::class, 'index'])->name('survey.index');
Route::post('/love-survey/submit', [LoveSurveyController::class, 'submitSurvey'])->name('survey.submit');
Route::get('/love-survey/complete', [LoveSurveyController::class, 'complete'])->name('survey.complete');
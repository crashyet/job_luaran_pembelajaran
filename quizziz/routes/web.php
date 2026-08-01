<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'index'])->name('dashboard');
Route::post('/simulate-user', [QuizController::class, 'simulateUser'])->name('simulate.user');
Route::post('/quiz/create', [QuizController::class, 'createQuiz'])->name('quiz.create');
Route::post('/quiz/{quiz}/question', [QuizController::class, 'addQuestion'])->name('quiz.question');
Route::post('/quiz/{quiz}/host', [QuizController::class, 'hostGame'])->name('quiz.host');
Route::post('/game/join', [QuizController::class, 'joinGame'])->name('game.join');
Route::get('/game-session/{code}', [QuizController::class, 'sessionView'])->name('game.session');
Route::get('/game-session/{code}/status', [QuizController::class, 'sessionStatus'])->name('game.status');
Route::post('/game-session/{code}/start', [QuizController::class, 'startGame'])->name('game.start');
Route::post('/game-session/{code}/answer', [QuizController::class, 'submitAnswer'])->name('game.answer');
Route::post('/game-session/{code}/next', [QuizController::class, 'nextQuestion'])->name('game.next');
Route::post('/game-session/{code}/end', [QuizController::class, 'endGame'])->name('game.end');

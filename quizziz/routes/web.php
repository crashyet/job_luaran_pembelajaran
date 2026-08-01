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

// CSV Template & Import
Route::get('/quiz/template/csv', [QuizController::class, 'downloadCSVTemplate'])->name('quiz.template.csv');
Route::post('/quiz/{quiz}/import', [QuizController::class, 'importQuestions'])->name('quiz.import');

// Solo Mode Routes
Route::get('/quiz/{quiz_code}/solo', [QuizController::class, 'startSoloPlay'])->name('quiz.solo');
Route::post('/quiz/{quiz_code}/solo/join', [QuizController::class, 'joinSoloPlay'])->name('quiz.solo.join');
Route::get('/quiz/{quiz_code}/solo/question', [QuizController::class, 'soloQuestionView'])->name('quiz.solo.question');
Route::post('/quiz/{quiz_code}/solo/answer', [QuizController::class, 'submitSoloAnswer'])->name('quiz.solo.answer');
Route::post('/quiz/{quiz_code}/solo/next', [QuizController::class, 'nextSoloQuestion'])->name('quiz.solo.next');
Route::get('/quiz/{quiz_code}/solo/result', [QuizController::class, 'soloResultView'])->name('quiz.solo.result');
Route::get('/quiz/{quiz_code}/reports', [QuizController::class, 'getSoloReports'])->name('quiz.reports');
Route::get('/quiz/{quiz_code}/reports/export', [QuizController::class, 'exportSoloReports'])->name('quiz.reports.export');


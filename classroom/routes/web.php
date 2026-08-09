<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [ClassroomController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/classroom/create', [ClassroomController::class, 'createClass'])->name('classroom.create');
    Route::post('/classroom/join', [ClassroomController::class, 'joinClass'])->name('classroom.join');
    Route::get('/classroom/{classroom}', [ClassroomController::class, 'show'])->name('classroom.show');
    Route::post('/classroom/{classroom}/post', [ClassroomController::class, 'storePost'])->name('classroom.post');
    Route::post('/post/{post}/comment', [ClassroomController::class, 'storeComment'])->name('post.comment');
    Route::post('/assignment/{post}/submit', [ClassroomController::class, 'submitAssignment'])->name('assignment.submit');
    Route::post('/submission/{submission}/grade', [ClassroomController::class, 'gradeSubmission'])->name('submission.grade');
});


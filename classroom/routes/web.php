<?php

use App\Http\Controllers\ClassroomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClassroomController::class, 'index'])->name('dashboard');
Route::post('/simulate-user', [ClassroomController::class, 'simulateUser'])->name('simulate.user');
Route::post('/classroom/create', [ClassroomController::class, 'createClass'])->name('classroom.create');
Route::post('/classroom/join', [ClassroomController::class, 'joinClass'])->name('classroom.join');
Route::get('/classroom/{classroom}', [ClassroomController::class, 'show'])->name('classroom.show');
Route::post('/classroom/{classroom}/post', [ClassroomController::class, 'storePost'])->name('classroom.post');
Route::post('/post/{post}/comment', [ClassroomController::class, 'storeComment'])->name('post.comment');
Route::post('/assignment/{post}/submit', [ClassroomController::class, 'submitAssignment'])->name('assignment.submit');
Route::post('/submission/{submission}/grade', [ClassroomController::class, 'gradeSubmission'])->name('submission.grade');

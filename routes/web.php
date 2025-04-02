<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/quiz/{ordering}/question', [QuizController::class, 'getQuestion']);

Route::get('/quiz/{id}', function ($id) {
    return view('quiz', ['quizId' => $id]);
});


// ordering
Route::get('/ordering', [OrderingController::class, 'index'])->name('ordering.index');
Route::post('/ordering', [OrderingController::class, 'store'])->name('ordering.store');
Route::put('/ordering/{id}', [OrderingController::class, 'update'])->name('ordering.update');
Route::delete('/ordering/{id}', [OrderingController::class, 'destroy'])->name('ordering.destroy');


Route::get('/ordering/{id}/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');

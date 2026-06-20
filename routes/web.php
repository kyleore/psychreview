<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
Route::get('/topics/{topic:slug}', [TopicController::class, 'show'])->name('topics.show');

Route::get('/flashcards', [FlashcardController::class, 'index'])->name('flashcards.index');

Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz', [QuizController::class, 'submit'])->name('quiz.submit');

Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::post('/library/find-pdfs', [LibraryController::class, 'findPdfs'])->name('library.find');

Route::get('/ai-tutor', [AiController::class, 'index'])->name('ai.index');
Route::post('/ai-tutor/explain', [AiController::class, 'explain'])->name('ai.explain');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

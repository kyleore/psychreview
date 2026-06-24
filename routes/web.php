<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

// Public entry point: intro/landing for guests, dashboard for logged-in users.
Route::get('/', [HomeController::class, 'index'])->name('home');

// The app itself requires an account.
Route::middleware('auth')->group(function () {
    // First-run feature walkthrough for new users.
    Route::get('/welcome', [HomeController::class, 'intro'])->name('intro');
    Route::post('/welcome/done', [HomeController::class, 'finishIntro'])->name('intro.done');

    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
    Route::get('/topics/{topic:slug}', [TopicController::class, 'show'])->name('topics.show');

    Route::get('/flashcards', [FlashcardController::class, 'index'])->name('flashcards.index');

    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::post('/quiz/generate', [QuizController::class, 'generate'])->middleware('admin')->name('quiz.generate');

    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/library/find-pdfs', [LibraryController::class, 'findPdfs'])->name('library.find');

    Route::get('/ai-tutor', [AiController::class, 'index'])->name('ai.index');
    Route::post('/ai-tutor/explain', [AiController::class, 'explain'])->name('ai.explain');
});

// Admin panel — monitor users, content and quiz activity.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

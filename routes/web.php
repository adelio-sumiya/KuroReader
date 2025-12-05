<?php

use App\Http\Controllers\AdminChapterController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadingHistoryController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NovelController::class, 'index'])->name('home');
Route::get('/novels/index', [NovelController::class, 'index'])->name('novels.index');
Route::get('/novels', [NovelController::class, 'search'])->name('novels.search');
Route::get('/novels/{apiId}', [NovelController::class, 'show'])->name('novels.show');

// User chapter reading routes (uploaded chapters)
Route::get('/chapters/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');
Route::get('/chapters/{chapter}/next', [ChapterController::class, 'next'])->name('chapters.next');
Route::get('/chapters/{chapter}/previous', [ChapterController::class, 'previous'])->name('chapters.previous');

// Simple Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    // Admin chapter management (simple is_admin check inside controller)
    Route::get('/admin/novels/{apiId}/chapters', [AdminChapterController::class, 'index'])->name('admin.chapters.index');
    Route::post('/admin/novels/{apiId}/chapters', [AdminChapterController::class, 'store'])->name('admin.chapters.store');
    Route::get('/admin/novels/{apiId}/chapters/{chapter}/edit', [AdminChapterController::class, 'edit'])->name('admin.chapters.edit');
    Route::delete('/admin/novels/{apiId}/chapters/{chapter}', [AdminChapterController::class, 'destroy'])->name('admin.chapters.destroy');
    // Library Management
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/library/add', [LibraryController::class, 'store'])->name('library.store');
    Route::put('/library/{id}/status', [LibraryController::class, 'updateStatus'])->name('library.update');
    Route::delete('/library/{id}', [LibraryController::class, 'destroy'])->name('library.destroy');

    // Reading History
    Route::post('/history', [ReadingHistoryController::class, 'update'])->name('history.update');
    Route::get('/history', [ReadingHistoryController::class, 'index'])->name('history.index');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // Email Verification
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');
});

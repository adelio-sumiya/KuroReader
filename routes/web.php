<?php

use App\Http\Controllers\NovelController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ReadingHistoryController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NovelController::class, 'index'])->name('home');
Route::get('/novels', [NovelController::class, 'search'])->name('novels.search');
Route::get('/novels/{apiId}', [NovelController::class, 'show'])->name('novels.show');

// Protected Routes (requires authentication)
Route::middleware(['auth'])->group(function () {
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
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

<?php

use App\Http\Controllers\NovelController;

Route::get('/', [NovelController::class, 'index']);
Route::get('/search', [NovelController::class, 'search']);
Route::get('/novel', [NovelController::class, 'show']);
Route::get('/read/{id}', [NovelController::class, 'read']);
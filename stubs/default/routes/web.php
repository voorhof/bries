<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/**
 * Index
 */
Route::get('/', [PageController::class, 'welcome'])->name('welcome');

/**
 * Pages for verified users
 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/cheatsheet', [PageController::class, 'cheatsheet'])->name('cheatsheet');
});

/**
 * User profile settings
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Auth Controllers
 */
require __DIR__.'/auth.php';

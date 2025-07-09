<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\Profile\ProfileController;
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
});

/**
 * User profile settings
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

/**
 * Auth Controllers
 */
require __DIR__.'/auth.php';

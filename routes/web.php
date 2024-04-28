<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/content-editor/{id}/{postId?}', [DashboardController::class, 'contentEditor'])->middleware(['auth', 'verified', 'check.telegram.key'])->name('content.editor');
Route::get('/content-history/{id}', [DashboardController::class, 'history'])->middleware(['auth', 'verified', 'check.telegram.key'])->name('content.history');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require base_path() . '/app-modules/auth/routes/auth-routes.php';

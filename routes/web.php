<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::get('up', static fn () => response()->json(['status' => 'up']))->name('health');

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'permission:users.manage'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])
        ->middleware(HandlePrecognitiveRequests::class)
        ->name('users.store');
    Route::patch('users/{user}', [UserController::class, 'update'])
        ->middleware(HandlePrecognitiveRequests::class)
        ->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SSOController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('sso.auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::get('/sso-redirect-foodpanda', [SSOController::class, 'redirectToFoodpanda'])
    ->name('sso.redirect.foodpanda')
    ->middleware('auth');



require __DIR__.'/auth.php';

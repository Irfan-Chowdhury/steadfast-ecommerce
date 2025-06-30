<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SSOController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/clear-config-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return 'Config and cache cleared!';
});


Route::get('/', function () {
    // return view('welcome');
    return Auth::check()
        ? redirect()->back()   // if logged in
        : redirect()->route('login');      // if guest
});

Route::middleware('sso.auth','auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('report.index');
    Route::post('/reports', [ReportController::class, 'getReportData'])->name('report.generate');
});



require __DIR__.'/auth.php';

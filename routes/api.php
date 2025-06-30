<?php

use App\Http\Controllers\SSOController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sso-logout', [SSOController::class, 'ssoLogout']);

Route::post('login', [AuthenticatedSessionController::class, 'store']);



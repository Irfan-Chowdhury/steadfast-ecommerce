<?php

use App\Http\Controllers\SSOController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/sso-logout', [SSOController::class, 'ssoLogout']);


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/google', [AuthController::class, 'google']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/set-password', [AuthController::class, 'setPassword']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads', [LeadController::class, 'index']);
});

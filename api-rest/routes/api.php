<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;

Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::get('/memberships', [MembershipController::class, 'index']);
Route::get('/memberships/{membership}', [MembershipController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Ruta para logout
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/memberships', [MembershipController::class, 'store']);
    Route::put('/memberships/{membership}', [MembershipController::class, 'update']);
    Route::delete('/memberships/{membership}', [MembershipController::class, 'destroy']);
});

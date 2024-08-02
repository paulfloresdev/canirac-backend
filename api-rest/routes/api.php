<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EventController;

Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::get('/memberships', [MembershipController::class, 'index']);
Route::get('/memberships/{membership}', [MembershipController::class, 'show']);

Route::post('events-filtered', [EventController::class, 'index']);
Route::get('events/upcoming', [EventController::class, 'upcoming']);
Route::get('events/{event}', [EventController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Ruta para logout
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/memberships', [MembershipController::class, 'store']);
    Route::put('/memberships/{membership}', [MembershipController::class, 'update']);
    Route::delete('/memberships/{membership}', [MembershipController::class, 'destroy']);

    Route::post('events', [EventController::class, 'store']);
    // Rutas para actualizar eventos
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::put('/events/{event}/details', [EventController::class, 'updateDetails']);
    Route::post('/events/{event}/update-vertical-image', [EventController::class, 'updateVerticalImage']);
    Route::post('/events/{event}/update-horizontal-image', [EventController::class, 'updateHorizontalImage']);
});

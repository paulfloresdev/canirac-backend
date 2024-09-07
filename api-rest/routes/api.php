<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChamberMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\JoinFormController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\ContactController;


// Auth
Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

// Memberships
Route::get('/memberships', [MembershipController::class, 'index']);
Route::get('/memberships/{membership}', [MembershipController::class, 'show']);

// Events
Route::post('events-filtered', [EventController::class, 'index']);
Route::get('events/upcoming', [EventController::class, 'upcoming']);
Route::get('events/{event}', [EventController::class, 'show']);

// Services
Route::post('services-filtered', [ServiceController::class, 'index']);
Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{service}', [ServiceController::class, 'show']);

// Labels
Route::get('labels', [LabelController::class, 'index']);
Route::get('labels/{label}', [LabelController::class, 'show']);
Route::get('show-video', [LabelController::class, 'showVideo']);

// JoinForm
Route::get('/joinforms', [JoinFormController::class, 'index']);
Route::get('/joinforms/{id}', [JoinFormController::class, 'show']);

// SocialMedia
Route::get('/socialmedia', [SocialMediaController::class, 'index']);
Route::get('/socialmedia/{id}', [SocialMediaController::class, 'show']);

// Contact
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/contact/{id}', [ContactController::class, 'show']);

// ChamberMembers
Route::get('/chamber-members', [ChamberMemberController::class, 'index']);
Route::get('/chamber-members/{id}', [ChamberMemberController::class, 'show']);


// AUTH SANCTUM
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Memberships
    Route::post('/memberships', [MembershipController::class, 'store']);
    Route::put('/memberships/{membership}', [MembershipController::class, 'update']);
    Route::delete('/memberships/{membership}', [MembershipController::class, 'destroy']);

    // Events
    Route::post('events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::put('/events/{event}/details', [EventController::class, 'updateDetails']);
    Route::post('/events/{event}/update-vertical-image', [EventController::class, 'updateVerticalImage']);
    Route::post('/events/{event}/update-horizontal-image', [EventController::class, 'updateHorizontalImage']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);

    // Services
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('/services/{service}', [ServiceController::class, 'update']);
    Route::put('/services/{service}/details', [ServiceController::class, 'update']);
    Route::post('/services/{service}/update-image', [ServiceController::class, 'updateImage']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

    // Labels
    Route::post('labels', [LabelController::class, 'store']);
    Route::put('labels/{label}', [LabelController::class, 'update']);
    Route::delete('labels/{label}', [LabelController::class, 'destroy']);
    Route::post('labels/upload-video', [LabelController::class, 'updateVideoLabel']);


    // JoinForms
    Route::put('/joinforms/{id}', [JoinFormController::class, 'update']);
    Route::delete('/joinforms/{id}', [JoinFormController::class, 'destroy']);

    Route::post('/joinforms', [JoinFormController::class, 'send']);
    Route::post('/joinforms/see/{id}', [JoinFormController::class, 'see']);
    Route::post('/joinforms/contacted/{id}', [JoinFormController::class, 'contacted']);
    Route::post('/joinforms/failed/{id}', [JoinFormController::class, 'failed']);
    Route::post('/joinforms/joined/{id}', [JoinFormController::class, 'joined']);
    Route::get('/joinforms-charts', [JoinFormController::class, 'getCharts']);

    // SocialMedias
    Route::post('/socialmedia', [SocialMediaController::class, 'store']);
    Route::put('/socialmedia/{id}', [SocialMediaController::class, 'update']);
    Route::delete('/socialmedia/{id}', [SocialMediaController::class, 'destroy']);

    // Contact
    Route::post('/contact', [ContactController::class, 'store']);
    Route::put('/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);

    // ChamberMembers
    Route::post('/chamber-members', [ChamberMemberController::class, 'store']);
    Route::put('/chamber-members/{id}', [ChamberMemberController::class, 'update']);
    Route::delete('/chamber-members/{id}', [ChamberMemberController::class, 'destroy']);
    Route::put('/chamber-members/{id}/update-data', [ChamberMemberController::class, 'updateData']);
    Route::post('/chamber-members/{id}/update-img', [ChamberMemberController::class, 'updateImage']);
    Route::delete('/chamber-members/delete/{id}', [ChamberMemberController::class, 'deleteMemberImage']);
});

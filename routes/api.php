<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class,'login']);

// Publicly accessible routes
// -- Events
Route::get('events', [EventController::class,'index']);
Route::get('events/{event}', [EventController::class,'show']);

// -- Attendees
Route::get('events/{event}/attendees', [AttendeeController::class,'index']);
Route::get('events/{event}/attendees/{attendee}', [EventController::class,'show']);

// Authenticated event routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class,'logout']);

    // Events
    Route::post('events', [EventController::class,'store']);
    Route::put('events/{event}', [EventController::class,'update']);
    Route::delete('events/{event}', [EventController::class,'destroy']);

    // Attendees
    Route::post('events/{event}/attendees', [AttendeeController::class,'store']);
    Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class,'destroy']);
});

Route::fallback(function () {
    return response()->json(['message' => 'This endpoint was not found!'], 404);
});

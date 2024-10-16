<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients/search', [ClientController::class, 'search']);
    Route::get('clients/{id}', [ClientController::class, 'show']);
    Route::put('clients/{id}', [ClientController::class, 'update']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);

    Route::get('appointments', [AppointmentController::class, 'index']);
    Route::post('appointments', [AppointmentController::class, 'store']);
    Route::get('appointments/view', [AppointmentController::class, 'indexView']);
    Route::get('appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);

    Route::get('services', [ServiceController::class, 'index']);
    Route::post('services', [ServiceController::class, 'store']);
    Route::get('services/{id}', [ServiceController::class, 'show']);
    Route::put('services/{id}', [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'destroy']);
});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('permission:role.view')->group(function () {
        Route::get('roles', [RoleController::class, 'index']);
    });

    Route::middleware('permission:user.view')->group(function () {
        Route::get('users', [UserController::class, 'index']);
    });
});

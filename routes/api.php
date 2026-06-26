<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('permission:' . Permission::ROLE_VIEW)->group(function () {
        Route::get('roles', [RoleController::class, 'index']);
    });

    Route::middleware('permission:' . Permission::USER_VIEW)->group(function () {
        Route::get('users', [UserController::class, 'index']);
    });
    Route::middleware('permission:' . Permission::USER_CREATE)->group(function () {
        Route::post('users', [UserController::class, 'store']);
    });
    Route::middleware('permission:' . Permission::USER_EDIT)->group(function () {
        Route::put('users/{user}', [UserController::class, 'update']);
    });
    Route::middleware('permission:' . Permission::USER_DELETE)->group(function () {
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });
});

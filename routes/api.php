<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BroadcastTestController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::post('broadcast-test', [BroadcastTestController::class, 'test']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('roles', [RoleController::class, 'index']);

    # user
    Route::get('users', [UserController::class, 'index'])->middleware('permission:' . Permission::USER_VIEW);
    Route::get('users/{id}', [UserController::class, 'show'])->middleware('permission:' . Permission::USER_VIEW);
    Route::post('users', [UserController::class, 'store'])->middleware('permission:' . Permission::USER_CREATE);
    Route::put('users/{id}', [UserController::class, 'update'])->middleware('permission:' . Permission::USER_EDIT);
    Route::delete('users/{id}', [UserController::class, 'destroy'])->middleware('permission:' . Permission::USER_DELETE);
});

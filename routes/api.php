<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserRoleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/users/{user}/roles', [UserRoleController::class, 'assign']);
    Route::put('/users/{user}/roles', [UserRoleController::class, 'update']);
    Route::delete('/users/{user}/roles', [UserRoleController::class, 'revoke']);
});

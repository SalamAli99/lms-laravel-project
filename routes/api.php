<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\CourseInstructorController;

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/users/{user}/roles', [UserRoleController::class, 'assign']);
    Route::put('/users/{user}/roles', [UserRoleController::class, 'update']);
    Route::delete('/users/{user}/roles', [UserRoleController::class, 'revoke']);
    Route::put(
            '/courses/{course}/instructor/{instructor}',
            [CourseInstructorController::class, 'assign']
        );
});

 Route::middleware('auth:sanctum','role:instructor')->group(function () {
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{course}', [CourseController::class, 'update']);
        Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
    });

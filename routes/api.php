<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Admin\CourseInstructorController;

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/lessons', [LessonController::class, 'index']);
    Route::get('/lessons/{lesson}', [LessonController::class, 'show']);
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

Route::middleware('auth:sanctum','role:admin')->group(function () {
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{course}', [CourseController::class, 'update']);
        Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
    });

Route::middleware(['auth:sanctum','role:admin,instructor'])->group(function () {
    Route::post('/lessons', [LessonController::class, 'store']);
    Route::put('/lessons/{lesson}', [LessonController::class, 'update']);
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy']);
});
Route::middleware(['auth:sanctum','role:student'])
    ->post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll']);

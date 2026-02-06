<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Admin\CourseInstructorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LatestNewsController;

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


    Route::get('courses/{course}/reviews', [ReviewController::class, 'index']);
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);
    
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/lessons', [LessonController::class, 'index']);
    Route::get('/lessons/{lesson}', [LessonController::class, 'show']);
    

});

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    
    Route::get('/latest', [LatestNewsController::class, 'index']);
    Route::post('/latest', [LatestNewsController::class, 'store']);
    Route::get('latest/{id}', [LatestNewsController::class, 'show']);
    Route::put('latest/{id}', [LatestNewsController::class, 'update']);
    Route::delete('latest/{id}', [LatestNewsController::class, 'destroy']);

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

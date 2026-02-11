<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserRoleResource;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use App\Traits\ApiResponse;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class EnrollmentController extends Controller
{
    use ApiResponse;

    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * List authenticated user's enrollments
     */
    public function index(): JsonResponse
    {
        $enrollments = Enrollment::with('course')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return $this->success(
            EnrollmentResource::collection($enrollments),
            'Enrollments fetched successfully'
        );
    }

    /**
     * Enroll authenticated user in a course
     */
    public function store(EnrollmentRequest $request): JsonResponse
    {
        try {
            $enrollment = $this->enrollmentService->enroll(
                auth()->id(),
                $request->course_id
            );

            return $this->success(
                new EnrollmentResource($enrollment->load(['user', 'course'])),
                'Enrolled successfully',
                201
            );
        } catch (DomainException $e) {
            return $this->error(
                $e->getMessage(),
                null,
                409 // Conflict
            );
        }
    }

    /**
     * Unenroll authenticated user from a course
     */
    public function destroy(int $courseId): JsonResponse
    {
        try {
            $this->enrollmentService->unenroll(
                auth()->id(),
                $courseId
            );

            return $this->success(
                null,
                'Unenrolled successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                'Enrollment not found',
                null,
                404
            );
        }
    }
    public function myCourses(): JsonResponse
{
    $courses = auth()->user()
        ->enrolledCourses()
        ->with('instructor')
        ->latest()
        ->get();

    return $this->success(
        CourseResource::collection($courses),
        'Enrolled courses fetched successfully'
    );
}
public function enrolledStudents(Course $course): JsonResponse
{
    $students = $course->students()
        ->latest()
        ->get();

    return $this->success(
        UserRoleResource::collection($students),
        'Students enrolled in course fetched successfully'
    );
}

public function updateStatus(
    Enrollment $enrollment,
    EnrollmentRequest  $request
): JsonResponse {
    try {
        $enrollment = $this->enrollmentService->updateStatus(
            $enrollment,
            $request->status
        );

        return $this->success(
            $enrollment,
            'Enrollment status updated successfully'
        );

    } catch (DomainException $e) {
        return $this->error(
            $e->getMessage(),
            null,
            422
        );
    }}
}

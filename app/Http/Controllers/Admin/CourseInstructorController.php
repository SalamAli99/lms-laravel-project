<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseInstructorService;
use App\Traits\ApiResponse;
use Exception;

class CourseInstructorController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CourseInstructorService $service
    ) {}

    public function assign(Course $course, User $instructor)
    {
        try {
            $course = $this->service->assign($course, $instructor);

            return $this->success(
                new CourseResource($course),
                'Instructor assigned to course successfully'
            );
        } catch (Exception $e) {
            return $this->error(
                'Failed to assign instructor',
                $e->getMessage(),
                422
            );
        }
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\EnrollmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class EnrollmentController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected EnrollmentService $service
    ) {}

    public function enroll(Course $course)
    {
        try {
            $this->service->enroll($course, auth()->user());

            return $this->success(
                null,
                'Enrolled successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error(
                'Enrollment failed',
                $e->getMessage(),
                403
            );
        }
    }
}


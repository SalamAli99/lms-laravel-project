<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\CourseFilterRequest;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use App\Traits\ApiResponse;
use Exception;

class CourseController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CourseService $service
    ) {}


public function index(CourseFilterRequest $request)
{
    try {
        $courses = $this->service->list($request->validated());

        return $this->success(
            CourseResource::collection($courses)->response()->getData(true),
            'Courses fetched successfully'
        );
    } catch (\Exception $e) {
        return $this->error(
            'Failed to fetch courses',
            $e->getMessage(),
            500
        );
    }
}


    public function store(StoreCourseRequest $request)
    {
        try {
            $course = $this->service->create($request->validated());

            return $this->success(
                new CourseResource($course),
                'Course created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->error('Failed to create course', $e->getMessage(), 500);
        }
    }

    public function show(Course $course)
{
    try {
        return $this->success(
            new CourseResource($course),
            'Course fetched successfully'
        );
    } catch (\Exception $e) {
        return $this->error(
            'Failed to fetch course',
            $e->getMessage(),
            500
        );
    }
}


    public function update(UpdateCourseRequest $request, Course $course)
    {
        try {
            $course = $this->service->update($course, $request->validated());

            return $this->success(
                new CourseResource($course),
                'Course updated successfully'
            );
        } catch (Exception $e) {
            return $this->error('Failed to update course', $e->getMessage(), 500);
        }
    }

    public function destroy(Course $course)
    {
        try {
            $this->service->delete($course);

            return $this->success(
                null,
                'Course deleted successfully'
            );
        } catch (Exception $e) {
            return $this->error('Failed to delete course', $e->getMessage(), 500);
        }
    }
}


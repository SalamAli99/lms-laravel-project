<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\Course;
use App\Services\LessonService;
use App\Traits\ApiResponse;
use Exception;

class LessonController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LessonService $service
    ) {}

    public function index()
    {
        try {
            return $this->success(
                LessonResource::collection($this->service->list()),
                'Lessons fetched successfully'
            );
        } catch (Exception $e) {
            return $this->error('Failed to fetch lessons', $e->getMessage(), 500);
        }

}

public function store(StoreLessonRequest $request)
{
    try {
        $lesson = $this->service->store(
            $request->validated(),
            auth()->user()
        );

        return $this->success(
            new LessonResource($lesson),
            'Lesson created successfully',
            201
        );
    } catch (\Exception $e) {
        return $this->error(
            'Failed to create lesson',
            $e->getMessage(),
            403
        );
    }
}


    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        try {
        $lesson = $this->service->update(
            $lesson,
            $request->validated(),
            auth()->user()
        );

        return $this->success(
            new LessonResource($lesson),
            'Lesson updated successfully'
        );
    } catch (\Exception $e) {
        return $this->error(
            'Failed to update lesson',
            $e->getMessage(),
            403
        );
    }
    }

    public function destroy(Lesson $lesson)
    {
        try {
            $this->service->delete($lesson);

            return $this->success(
                null,
                'Lesson deleted successfully'
            );
        } catch (Exception $e) {
            return $this->error('Failed to delete lesson', $e->getMessage(), 500);
        }
    }


    public function show(Lesson $lesson)
{
    // 🔐 Policy check (enrollment / ownership / admin)
    $this->authorize('view', $lesson);

    try {
        $lesson = $this->service->show(
            $lesson,
            auth()->user()
        );

        return $this->success(
            new LessonResource($lesson),
            'Lesson fetched successfully'
        );
    } catch (\Exception $e) {
        return $this->error(
            'Failed to fetch lesson',
            $e->getMessage(),
            403
        );
    }
}
}
<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    /////////////////////////////////

    public function list(array $filters = [])
{
    $query = Course::query()->with('instructor','lessons');

    if (!empty($filters['search'])) {
        $query->where('title', 'like', '%' . $filters['search'] . '%');
    }

    // price filters must ignore free courses
    if (isset($filters['min_price']) || isset($filters['max_price'])) {
        $query->where('is_paid', true);

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
    }

    if (!empty($filters['instructor_id'])) {
        $query->where('user_id', $filters['instructor_id']);
    }

    return $query->latest()->paginate($filters['per_page'] ?? 10);
}



/////////////////////////////////////////////////
public function create(array $data): Course
{
    $images = $data['images'] ?? [];
    $files  = $data['files'] ?? [];

    unset($data['images'], $data['files']);

    $data['user_id'] = Auth::id();

    $course = Course::create($data);

    foreach ((array)$images as $image) {
        if ($image) {
            $course->addMedia($image)
                ->usingName($image->getClientOriginalName())
                ->toMediaCollection('images', 'public');
        }
    }

    foreach ((array)$files as $file) {
        if ($file) {
            $course->addMedia($file)
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files', 'public');
        }
    }

    return $course->load('instructor');
}




public function update(Course $course, array $data): Course
{
    $images = $data['images'] ?? null;
    $files  = $data['files'] ?? null;

    unset($data['images'], $data['files']);

    $course->update($data);

    if ($images !== null) {
        $course->clearMediaCollection('images');

        foreach ($images as $image) {
            $course->addMedia($image)->toMediaCollection('images', 'public');
        }
    }

    if ($files !== null) {
        $course->clearMediaCollection('files');

        foreach ($files as $file) {
            $course->addMedia($file)->toMediaCollection('files', 'public');
        }
    }

    return $course->fresh()->load('instructor');
}

public function delete(Course $course): void
{
    if ($course->user_id !== Auth::id()) {
        throw new \Exception('Unauthorized');
    }

    $course->clearMediaCollection('images');
    $course->clearMediaCollection('files');
    $course->delete();
}


    public function show(Course $course): Course
{
    return $course->load([
        'instructor',
        'lessons' => function ($query) {
            if (!Auth::user()?->hasRole('instructor')) {
                $query->where('is_published', true);
            }
        },
        'students',
    ]);
}

}

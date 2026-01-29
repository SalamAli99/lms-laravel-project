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

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (! empty($filters['instructor_id'])) {
            $query->where('user_id', $filters['instructor_id']);
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->latest()->paginate($perPage);
    }


/////////////////////////////////////////////////
public function create(array $data): Course
{
    $images = $data['images'] ?? null;
    $files  = $data['files'] ?? null;

    unset($data['images'], $data['files']);

    $data['user_id'] = auth()->id();
    $course = Course::create($data);

   
    $images = is_array($images) ? $images : [$images];

if (!empty($images)) {
    foreach ($images as $image) {
        $course
            ->addMedia($image)
            ->usingName($image->getClientOriginalName()) // Preserve original name
            ->toMediaCollection('images', 'public');
    }
}

if (!empty($files)) {
    foreach ($files as $file) {
        $course
            ->addMedia($file)
            ->usingName($file->getClientOriginalName()) // Preserve original name
            ->toMediaCollection('files', 'public');
    }
}

    return $course->fresh();
}



   public function update(Course $course, array $data): Course
{
    $images = $data['images'] ?? null;
    $files  = $data['files'] ?? null; // <- add this line

    unset($data['images'], $data['files']); // remove both from $data before update

    // Update the other course fields
    $course->update($data);

    // Handle images (if any)
    if ($images) {
        foreach ($images as $image) {
            $course->addMedia($image)->toMediaCollection('images');
        }
    }

    // Handle files (if any)
    if ($files) {
        foreach ($files as $file) {
            $course->addMedia($file)->toMediaCollection('files');
        }
    }

    return $course->fresh();
}


    public function delete(Course $course, $user): void
{
    
        $course->clearMediaCollection('images');
        $course->clearMediaCollection('files');
   

    $course->delete();
}

    public function show(Course $course, User $user): Course
    {
        // Student → only published courses
        if ($user->hasRole('student') && ! $course->is_published) {
            throw new Exception('Course is not published');
        }

        // Instructor → only own courses
        if (
            $user->hasRole('instructor') &&
            $course->user_id !== $user->id
        ) {
            throw new Exception('You do not own this course');
        }

        // Load relations needed by CourseResource
        return $course->load([
            'instructor',
            'lesson' => function ($query) use ($user) {
                // Students see only published lessons
                if ($user->hasRole('student')) {
                    $query->where('is_published', true);
                }
            },
        ]);
    }
}

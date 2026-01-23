<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    /////////////////////////////////

    public function list(array $filters = [])
    {
        $query = Course::query()->with('instructor');

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

   
    if (!empty($images)) {
        foreach ($images as $image) {
            $course
                ->addMedia($image)
                ->toMediaCollection('images');
        }
    }

    
    if (!empty($files)) {
        foreach ($files as $file) {
            $course
                ->addMedia($file)
                ->toMediaCollection('files');
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
    if ($user->hasRole('admin')) {
        $course->clearMediaCollection('images');
        $course->clearMediaCollection('files');
    }

    $course->delete();
}

}

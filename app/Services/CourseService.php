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
        $data['user_id'] = Auth::id();
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);
        return $course->fresh();
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }
}

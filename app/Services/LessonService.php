<?php
namespace App\Services;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\User;
use Exception;
class LessonService
{
    public function list()
    {
        return Lesson::orderBy('order')->paginate(10);
    }

    
    public function update(Lesson $lesson, array $data, User $user): Lesson
    {
        $files = $data['files'] ?? null;
        unset($data['files']);

        // Only admin can manage lesson files
        if ($files && ! $user->hasRole('admin')) {
            throw new Exception('Only admin can manage lesson files');
        }

        $lesson->update($data);

        if ($files) {
            foreach ($files as $file) {
                $lesson
                    ->addMedia($file)
                    ->toMediaCollection('files');
            }
        }

        return $lesson->fresh();
    }

    public function delete(Lesson $lesson): void
    {
        $lesson->delete();
    }

    public function store(array $data, User $user): Lesson
    {
        $files = $data['files'] ?? null;
        unset($data['files']);

        $course = Course::findOrFail($data['course_id']);

        if ($user->hasRole('student')) {
            throw new Exception('Students cannot create lessons');
        }

        if (
            $user->hasRole('instructor') &&
            $course->user_id !== $user->id
        ) {
            throw new Exception('You do not own this course');
        }

        $lesson = Lesson::create([
            'course_id'   => $course->id,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'order'       => $data['order'],
        ]);

        if ($files) {
            if (! $user->hasRole('admin')) {
                throw new Exception('Only admin can attach lesson files');
            }

            foreach ($files as $file) {
                $lesson
                    ->addMedia($file)
                    ->toMediaCollection('files');
            }
        }

        return $lesson->fresh();
    }


    public function show(Lesson $lesson, User $user): Lesson
    {

        return $lesson->load([
            'course',
            'course.instructor',
            'media',
        ]);
    }
}

<?php
namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function view(User $user, Lesson $lesson): bool
    {
        $course = $lesson->course;

        if ($user->hasRole('admin')) {
            return true;
        }

        if (
            $user->hasRole('instructor') &&
            $course->user_id === $user->id
        ) {
            return true;
        }

        if ($user->hasRole('student')) {
            return $user->enrolledCourses()
                ->where('course_id', $course->id)
                ->exists();
        }

        return false;
    }
}

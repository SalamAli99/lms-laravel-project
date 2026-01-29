<?php
namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Exception;

class EnrollmentService
{
    public function enroll(Course $course, User $user): void
    {
        if (! $user->hasRole('student')) {
            throw new Exception('Only students can enroll in courses');
        }

        if (
            $user->enrolledCourses()
                ->where('course_id', $course->id)
                ->exists()
        ) {
            throw new Exception('You are already enrolled in this course');
        }

        $user->enrolledCourses()->attach($course->id);
    }
}

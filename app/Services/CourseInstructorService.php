<?php
namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Exception;

class CourseInstructorService
{
    public function assign(Course $course, User $instructor): Course
    {
        if (! $instructor->hasRole('instructor')) {
            throw new Exception('User is not an instructor');
        }

        $course->user_id = $instructor->id;
        $course->save();

        return $course->fresh('instructor');
    }
}

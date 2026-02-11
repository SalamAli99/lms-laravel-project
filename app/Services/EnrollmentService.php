<?php
namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;

use Exception;

class EnrollmentService
{
    public function enroll(int $userId, int $courseId): Enrollment
   {
    if ($this->isEnrolled($userId, $courseId)) {
            throw new \DomainException('User already enrolled in this course.');
        }

        return Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'status'      => 'active',
            'enrolled_at' => now(),
        ]);
   }

   public function updateStatus(
        Enrollment $enrollment,
        string $status
    ): Enrollment {
        if (! in_array($status, [
            'pending',
            'active',
            'completed',
            'cancelled'
        ])) {
            throw new DomainException('Invalid enrollment status.');
        }

        $enrollment->update([
            'status' => $status
        ]);

        return $enrollment;
    }
   public function unenroll(int $userId, int $courseId): void
    {
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (! $enrollment) {
            throw new ModelNotFoundException('Enrollment not found.');
        }

        $enrollment->delete();
    }


    public function isEnrolled(int $userId, int $courseId): bool
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }
}

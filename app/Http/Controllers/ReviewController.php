<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;

    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index($courseId)
    {
        $reviews = Review::with('user')
            ->where('course_id', $courseId)
            ->orderBy('sort_order')
            ->get();

        return $this->success($reviews, 'Reviews fetched successfully');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'course_id'  => 'required|exists:courses,id',
            'sort_order' => 'nullable|integer',
        ]);

        $review = $this->reviewService->create($data);

        return $this->success($review, 'Review created successfully', 201);
    }

    public function show(Review $review)
    {
        return $this->success(
            $review->load(['user', 'course']),
            'Review fetched successfully'
        );
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'sort_order' => 'required|integer',
        ]);

        $review = $this->reviewService->update($review, $data);

        return $this->success($review, 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $this->reviewService->delete($review);

        return $this->success(null, 'Review deleted successfully');
    }

    
}

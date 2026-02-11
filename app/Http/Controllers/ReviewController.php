<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    use ApiResponse;

    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index()
    {
       try {
            return $this->success(
                ReviewResource::collection($this->reviewService->list()),
                'Reviews fetched successfully'
            );
        } catch (Exception $e) {
            return $this->error('Failed to fetch Reviews', $e->getMessage(), 500);
        }
    }

    public function store(ReviewRequest $request)
    {  try {
        $review = $this->reviewService->store(
            $request->validated()
        );

        return $this->success(
            new ReviewResource($review),
            'review created successfully',
            201
        );
    } catch (\Exception $e) {
        return $this->error(
            'Failed to create review',
            $e->getMessage(),
            403
        );
    }
    }

    public function show(Review $review)
    {
        return $this->success(
            $review->load(['user', 'course']),
            'Review fetched successfully'
        );
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $data = $request->validated();

        $review = $this->reviewService->update($review, $data);

        return $this->success($review, 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $this->reviewService->delete($review);

        return $this->success(null, 'Review deleted successfully');
    }

    
}

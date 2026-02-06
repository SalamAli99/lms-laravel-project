<?php

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    public function create(array $data): Review
    {
        return Review::create($data);
    }

    public function update(Review $review, array $data): Review
    {
        $review->update($data);
        return $review;
    }

    public function delete(Review $review): void
    {
        $review->delete();
    }

    
}

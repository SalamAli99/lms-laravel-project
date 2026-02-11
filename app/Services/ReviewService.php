<?php

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    public function list()
    {
        return Review::paginate(10);
    }
    public function store(array $data): Review
    {
        return Review::create($data);
    }
    public function show(int $id): Review
    {
          return Review::findOrFail($id);
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

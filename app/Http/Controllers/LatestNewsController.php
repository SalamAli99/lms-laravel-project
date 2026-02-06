<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLatestNewsRequest;
use App\Http\Resources\LatestNewsResource;
use App\Services\LatestNewsService;
use App\Traits\ApiResponse;

class LatestNewsController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LatestNewsService $latestNewsService
    ) {}

    /**
     * List all news
     */
    public function index()
    {
        $news = $this->latestNewsService->list();

        return $this->success(
            LatestNewsResource::collection($news),
            'Latest news list fetched successfully'
        );
    }

    /**
     * Store new news
     */
    public function store(StoreLatestNewsRequest $request)
    {
        $news = $this->latestNewsService->create($request->validated());

        return $this->success(
            new LatestNewsResource($news),
            'Latest news created successfully',
            201
        );
    }

    /**
     * Show single news
     */
    public function show(int $id)
    {
        $news = $this->latestNewsService->show($id);

        return $this->success(
            new LatestNewsResource($news),
            'Latest news details fetched successfully'
        );
    }

     public function update(StoreLatestNewsRequest $request, int $id)
    {
        $news = $this->latestNewsService->update($id, $request->validated());

        return $this->success(
            new LatestNewsResource($news),
            'Latest news updated successfully'
        );
    }

    /**
     * Delete news
     */
    public function destroy(int $id)
    {
        $this->latestNewsService->delete($id);

        return $this->success(
            null,
            'Latest news deleted successfully'
        );
    }
}

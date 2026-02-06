<?php

namespace App\Services;

use App\Models\LatestNews;

class LatestNewsService
{
    public function create(array $data): LatestNews
    {
        return LatestNews::create($data);
    }

    public function list()
    {
        return LatestNews::orderBy('s_order')->get();
    }

    public function show(int $id): LatestNews
    {
        return LatestNews::findOrFail($id);
    }

    public function update(int $id, array $data): LatestNews
    {
        $news = LatestNews::findOrFail($id);
        $news->update($data);

        return $news;
    }

    public function delete(int $id): bool
    {
        $news = LatestNews::findOrFail($id);
        return $news->delete();
    }
}

<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'instructor' => [
                'id' => $this->instructor->id,
                'name' => $this->instructor->name,
            ],
            'images' => $this->getMedia('images')->map(fn ($media) => [
                'id'  => $media->id,
                'url' => $media->getUrl(),
            ]),
            
'files' => $this->getMedia('files')->map(fn ($media) => [
    'id'   => $media->id,
    'name' => $media->name,
    'url'  => $media->getUrl(),
]),
            'created_at' => $this->created_at,
            
        ];
    }
}

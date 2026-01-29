<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'course_id'    => $this->course_id,
            'title'        => $this->title,
            'description'  => $this->description,
            'video_url'    => $this->video_url,
            'order'        => $this->order,
            'is_published' => $this->is_published,
            'files' => $this->getMedia('files')->map(fn ($media) => [
                'id'   => $media->id,
                'name' => $media->name,
                'url'  => $media->getUrl(),
            ]),
            'created_at'   => $this->created_at,
        ];
    }
}

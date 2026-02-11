<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'course_id'    => $this->course_id,
            'user_id'        => $this->user_id,
            'comment'        => $this->comment,
            'created_at'   => $this->created_at,
        ];
    }
}

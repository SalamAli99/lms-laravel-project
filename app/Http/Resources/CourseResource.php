<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class CourseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'pricing_type' => $this->is_paid ? 'paid' : 'free',
            'price' => $this->is_paid ? $this->price : 'Free',
            'user_id'=>$this->user_id,
            'created_at' => $this->created_at,
            
        ];
    }
}

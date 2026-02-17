<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'code' => $this->code,

            'discount' => [
                'type' => $this->type,
                'value' => $this->value,
            ],

            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'remaining_uses' => $this->usage_limit
                ? max(0, $this->usage_limit - $this->used_count)
                : null,

            'expires_at' => optional($this->expires_at)->toDateTimeString(),
            'is_active' => $this->is_active,
            'is_valid' => $this->isValid(),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

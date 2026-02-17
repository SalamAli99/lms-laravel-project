<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    protected $fillable = [
        'course_id',
        'code',
        'type',
        'value',
        'usage_limit',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;

        if ($this->expires_at && $this->expires_at->isPast()) return false;

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) return false;

        return true;
    }
    public function applyDiscount(float $price): float
    {
        if ($this->type === 'fixed') {
            return max(0, $price - $this->value);
        }

        return max(0, $price - ($price * ($this->value / 100)));
    }
    public function users()
{
    return $this->belongsToMany(User::class)
        ->withPivot('used_at');
}
public function usedBy(User $user): bool
{
    return $this->users()->where('user_id', $user->id)->exists();
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Course extends Model implements HasMedia
{
        use InteractsWithMedia;

        public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('files'); 
    }
    protected $fillable = [
        'title',
        'description',
        'price',
        'is_paid',
        'user_id',
    ];
    protected static function booted()
    {
        // Auto handle price logic
        static::saving(function ($course) {

            if (!$course->is_paid) {
                $course->price = null;
            }

            if ($course->is_paid && empty($course->price)) {
                throw new \Exception('Paid course must have a price');
            }
        });
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
        
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    
public function students()
{
    return $this->belongsToMany(
        User::class,
        'enrollments'
    )->withTimestamps()
     ->withPivot('enrolled_at');
}
    public function reviews()
{
    return $this->belongsToMany(Review::class);
}
 public function isFree(): bool
    {
        return !$this->is_paid;
    }

    public function isPaid(): bool
    {
        return $this->is_paid;
    }
    public function coupons()
{
    return $this->hasMany(Coupon::class);
}
}

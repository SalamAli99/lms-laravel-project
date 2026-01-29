<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Lesson extends Model implements HasMedia
{
        use InteractsWithMedia;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

     public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->useDisk('public');
    }
}

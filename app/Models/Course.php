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
        'user_id',
    ];

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
    return $this->belongsToMany(User::class, 'course_user')
        ->withTimestamps();
}


}

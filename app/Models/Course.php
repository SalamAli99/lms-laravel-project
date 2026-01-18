<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
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
}

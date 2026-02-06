<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class LatestNews extends Model
{
            protected $table = 'latestnews';


    protected $fillable = [
        'title',
        'description',
        's_order',
    ];

    
}

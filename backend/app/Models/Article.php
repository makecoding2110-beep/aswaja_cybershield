<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'author',
        'read_time',
        'status',
        'cover_image',
    ];
}


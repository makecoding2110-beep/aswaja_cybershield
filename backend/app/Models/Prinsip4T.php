<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prinsip4T extends Model
{
    protected $table = 'prinsip_4t';

    protected $fillable = [
        'code',
        'name',
        'arabic',
        'tagline',
        'description',
        'link',
        'icon',
    ];
}


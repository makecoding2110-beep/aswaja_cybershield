<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    protected $table = 'case_studies';

    protected $fillable = [
        'title',
        'scenario',
        'sender',
        'message',
        'risk_level',
        'fiqih_principle',
        'question',
        'options',
        'discussion',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}


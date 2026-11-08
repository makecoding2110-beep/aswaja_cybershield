<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'url',
        'reporter',
        'category',
        'description',
        'evidence_path',
        'status',
        'verification_notes',
        'handled_by',
        'ai_analysis',
    ];

    protected $casts = [
        'ai_analysis' => 'array',
    ];
}


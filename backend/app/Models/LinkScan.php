<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent untuk Riwayat Audit Pemeriksaan Tautan
 * Sesuai Dokumen Spesifikasi Bagian 10
 */
class LinkScan extends Model
{
    protected $table = 'link_scans';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'original_url',
        'normalized_url',
        'domain',
        'scanned_at',
        'suricata_result',
        'gemini_result',
        'ai_comparison',
        'indicators',
        'techniques',
        'final_category',
        'risk_level',
        'risk_score',
        'classification_reason',
        'decision_source',
        'scan_status',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'suricata_result' => 'array',
        'gemini_result' => 'array',
        'ai_comparison' => 'array',
        'indicators' => 'array',
        'techniques' => 'array',
        'risk_score' => 'integer',
    ];
}


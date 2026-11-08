<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Klasifikasi Top-Level Domain (TLD) untuk Audit Keamanan Tautan
    |--------------------------------------------------------------------------
    */

    // Domain Instansi Pemerintah & Lembaga Pendidikan Indonesia
    // Target utama Web Defacement & Subdomain Hijacking oleh sindikat judol
    'protected_gov_edu' => [
        'go.id',
        'ac.id',
        'sch.id',
        'mil.id',
        'desa.id'
    ],

    // TLD Murah / Disposable / Fast-Flux yang sering disalahgunakan sindikat
    'suspicious_cheap_tlds' => [
        'xyz', 'top', 'cc', 'vip', 'fun', 'click', 'site', 'live', 'club',
        'icu', 'monster', 'cfd', 'rest', 'sbs', 'buzz', 'bet', 'casino',
        'lat', 'pw', 'win', 'tokyo', 'casa', 'link', 'work', 'date', 'trade',
        'online', 'pro', 'space', 'shop', 'website', 'quest'
    ],

    // TLD Komersial / Umum
    'general_tlds' => [
        'com', 'net', 'org', 'info', 'biz', 'id', 'co.id', 'web.id', 'or.id'
    ]
];


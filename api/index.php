<?php
/**
 * Aswaja CyberShield - Vercel Serverless Function Entrypoint 
 */

// 1. Inisialisasi storage di /tmp (karena filesystem Vercel Serverless bersifat read-only)
$tmpStorage = '/tmp/storage';
putenv("APP_STORAGE={$tmpStorage}");
$_ENV['APP_STORAGE'] = $tmpStorage;

// Siapkan subdirektori storage yang dibutuhkan oleh Laravel & Blade
$requiredDirs = [
    $tmpStorage,
    $tmpStorage . '/app',
    $tmpStorage . '/framework',
    $tmpStorage . '/framework/cache',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/framework/views',
    $tmpStorage . '/logs',
    $tmpStorage . '/views',
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// 2. Normalisasi Request URI
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($rawUri, PHP_URL_PATH) ?: '/';

// 3. Jika permintaan mengarah ke REST API (/api/*), teruskan langsung ke Laravel public/index.php
if (preg_match('#^/api(/|$)#', $requestPath)) {
    // Pastikan autoloader composer tersedia
    if (file_exists(__DIR__ . '/../backend/vendor/autoload.php')) {
        require_once __DIR__ . '/../backend/vendor/autoload.php';
    } elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    require __DIR__ . '/../backend/public/index.php';
    exit;
}

// 4. Untuk semua rute frontend dan admin, jalankan smart router index.php di root
require __DIR__ . '/../index.php';


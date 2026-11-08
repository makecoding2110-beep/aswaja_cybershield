<?php
/**
 * Vercel Serverless Entrypoint
 */

// Storage Setup
$tmpStorage = '/tmp/storage';
putenv("APP_STORAGE={$tmpStorage}");
$_ENV['APP_STORAGE'] = $tmpStorage;

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

// Request URI
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($rawUri, PHP_URL_PATH) ?: '/';

// API Router
if (preg_match('#^/api(/|$)#', $requestPath)) {
    if (file_exists(__DIR__ . '/../backend/vendor/autoload.php')) {
        require_once __DIR__ . '/../backend/vendor/autoload.php';
    } elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    require __DIR__ . '/../backend/public/index.php';
    exit;
}

// Frontend Router
require __DIR__ . '/../index.php';

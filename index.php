<?php
/**
 * Aswaja CyberShield - Smart Front Controller & Clean URL Router
 * Mendukung akses URL bersih tanpa ekstensi .blade.php / .php di lokal (php -S / Apache / Laragon) maupun Vercel.
 */

$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestUri = parse_url($rawUri, PHP_URL_PATH) ?: '/';

// 1. Sajikan aset logo dan favicon di path apa pun (misal /tabayyun/logo-shield.png, /logo-shield.png, dll.)
if (preg_match('#/(logo-shield\.png|favicon\.svg)$#', $requestUri, $matches)) {
    $assetFile = __DIR__ . '/' . $matches[1];
    if (is_file($assetFile)) {
        header('Content-Type: ' . ($matches[1] === 'favicon.svg' ? 'image/svg+xml' : 'image/png'));
        readfile($assetFile);
        exit;
    }
}

// 2. Jika request mengarah ke file aset statis fisik (css, js, png, svg, ico, json, font, dll.), sajikan langsung
$physicalPath = __DIR__ . '/' . ltrim($requestUri, '/');
if ($requestUri !== '/' && is_file($physicalPath) && !preg_match('/\.(php|blade\.php)$/i', $physicalPath)) {
    return false;
}

// 3. Normalisasi prefix subfolder (misal /aswajaCyber/)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$subDir = ($scriptDir !== '/' && $scriptDir !== '.') ? $scriptDir : '';
$uriPath = $requestUri;
if ($subDir !== '' && strpos($uriPath, $subDir) === 0) {
    $uriPath = substr($uriPath, strlen($subDir)) ?: '/';
}

// 4. Normalisasi rute (bersihkan semua duplikasi /frontend dan ekstensi .blade.php / .php)
$route = '/' . trim($uriPath, '/');
$route = preg_replace('#^(/frontend)+(/|$)#', '/', $route);
$route = preg_replace('/\.blade\.php$/i', '', $route);
$route = preg_replace('/\.php$/i', '', $route);
if ($route === '' || $route === '/index') {
    $route = '/';
}
if ($route === '/admin/index') {
    $route = '/admin';
}
if ($route === '/admin/login') {
    $route = '/login';
}

// 5. Pemetaan Rute Clean URL ke Berkas Blade
$routesMap = [
    '/'                   => __DIR__ . '/frontend/index.blade.php',
    '/tabayyun'           => __DIR__ . '/frontend/tabayyun.blade.php',
    '/etika'              => __DIR__ . '/frontend/etika.blade.php',
    '/cyber-guard'        => __DIR__ . '/frontend/cyber-guard.blade.php',
    '/lapor'              => __DIR__ . '/frontend/lapor.blade.php',
    '/login'              => __DIR__ . '/frontend/admin/login.blade.php',
    '/admin'              => __DIR__ . '/frontend/admin/index.blade.php',
    '/admin/artikel'      => __DIR__ . '/frontend/admin/artikel.blade.php',
    '/admin/laporan'      => __DIR__ . '/frontend/admin/laporan.blade.php',
    '/admin/studi-kasus'  => __DIR__ . '/frontend/admin/studi-kasus.blade.php',
    '/admin/integrasi-ai' => __DIR__ . '/frontend/admin/integrasi-ai.blade.php',
    '/admin/akun'         => __DIR__ . '/frontend/admin/akun.blade.php',
];

if (isset($routesMap[$route]) && is_file($routesMap[$route])) {
    require $routesMap[$route];
    exit;
}

// 6. Rute Dinamis Artikel Detail: /artikel/{slug}
if (preg_match('#^/artikel/([a-zA-Z0-9_-]+)$#', $route, $matches)) {
    $articleFile = __DIR__ . '/frontend/artikel/' . $matches[1] . '.blade.php';
    if (is_file($articleFile)) {
        require $articleFile;
        exit;
    }
}

// 7. Rute Dinamis Panel Admin: /admin/{page}
if (preg_match('#^/admin/([a-zA-Z0-9_-]+)$#', $route, $matches)) {
    $adminFile = __DIR__ . '/frontend/admin/' . $matches[1] . '.blade.php';
    if (is_file($adminFile)) {
        require $adminFile;
        exit;
    }
}

// 8. Fallback ke Beranda Utama
require __DIR__ . '/frontend/index.blade.php';
exit;

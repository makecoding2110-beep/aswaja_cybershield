<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=== TEST SUITE: RATE LIMIT PELAPORAN (MAX 2x / 2 JAM) ===" . PHP_EOL;

$ip = '10.99.88.77';
\Illuminate\Support\Facades\RateLimiter::clear('report_submission:' . $ip);

// Attempt 1: Harusnya 201 Created (Sisa kuota: 1)
$req1 = \Illuminate\Http\Request::create('/api/reports', 'POST', [
    'category' => 'judol',
    'url' => 'https://test-ratelimit-1.com',
    'reporter_name' => 'Warga 1',
    'description' => 'Aduan pertama'
], [], [], ['REMOTE_ADDR' => $ip]);
$res1 = $kernel->handle($req1);
$data1 = json_decode($res1->getContent(), true);
echo "Attempt 1: HTTP " . $res1->getStatusCode() . " | Sisa: " . ($data1['quota']['remaining'] ?? 'N/A') . PHP_EOL;

// Attempt 2: Harusnya 201 Created (Sisa kuota: 0)
$req2 = \Illuminate\Http\Request::create('/api/reports', 'POST', [
    'category' => 'judol',
    'url' => 'https://test-ratelimit-2.com',
    'reporter_name' => 'Warga 1',
    'description' => 'Aduan kedua'
], [], [], ['REMOTE_ADDR' => $ip]);
$res2 = $kernel->handle($req2);
$data2 = json_decode($res2->getContent(), true);
echo "Attempt 2: HTTP " . $res2->getStatusCode() . " | Sisa: " . ($data2['quota']['remaining'] ?? 'N/A') . PHP_EOL;

// Attempt 3: Harusnya 429 Too Many Requests
$req3 = \Illuminate\Http\Request::create('/api/reports', 'POST', [
    'category' => 'judol',
    'url' => 'https://test-ratelimit-3.com',
    'reporter_name' => 'Warga 1',
    'description' => 'Aduan ketiga melampaui kuota'
], [], [], ['REMOTE_ADDR' => $ip]);
$res3 = $kernel->handle($req3);
$data3 = json_decode($res3->getContent(), true);
echo "Attempt 3: HTTP " . $res3->getStatusCode() . " | Error: " . ($data3['error'] ?? 'N/A') . PHP_EOL;
echo "Pesan: " . ($data3['message'] ?? 'N/A') . PHP_EOL;
echo "Jumlah Alasan Keamanan: " . count($data3['reasons'] ?? []) . PHP_EOL;

// Bersihkan data dummy test
if (!empty($data1['data']['id'])) {
    \App\Models\Report::destroy($data1['data']['id']);
}
if (!empty($data2['data']['id'])) {
    \App\Models\Report::destroy($data2['data']['id']);
}
\Illuminate\Support\Facades\RateLimiter::clear('report_submission:' . $ip);

if ($res1->getStatusCode() === 201 && $res2->getStatusCode() === 201 && $res3->getStatusCode() === 429 && count($data3['reasons'] ?? []) === 4) {
    echo "✅ SEMUA PENGUJIAN RATE LIMIT BERHASIL SEMPURNA (2x/2 JAM + HTTP 429 + 4 ALASAN KEAMANAN)!" . PHP_EOL;
    exit(0);
} else {
    echo "❌ PENGUJIAN RATE LIMIT GAGAL!" . PHP_EOL;
    exit(1);
}

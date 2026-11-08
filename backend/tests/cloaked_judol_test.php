<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$orchestrator = app(App\Services\AiOrchestratorService::class);

$urls = [
    "Gambar 1" => "https://globalhearthospital.com/amp",
    "Gambar 2" => "https://hibiscus-turkey-yzas.squarespace.com/?gad_source=1",
    "Gambar 3" => "https://aaucauniversity.com/parlay/?gad_source=1&gad_campaignid=24446189686&gbraid=0AAAABBCuTIi5HqQvG8no1AfsXa_2sxHBg&gclid=CjwKCAiAtbvXBhB_EiwAjwv9Jp-TlkDEBN59RzNJir_jR0OVI3alr70mSQMgDVGm8YN2z6UHDuV_1hoCI7YQAvD_BwE",
    "Gambar 4" => "https://hkapekkk.mssg.me/?gad_source=1",
    "Gambar 5" => "https://cancercarecoaching.com/contact/?gad_source=1&gad_campaignid=24447382765&gbraid=0AAAAA_dMunlH_maFxlG9s533cmN3a7xNf&gclid=CjwKCAiAtbvXBhB_EiwAjwv9JouyVHtNZa7jt_rsjjkUTq_cRKRUVLMnsGRFlfptM32TnH3_0iyI6RoCDL8QAvD_BwE",
    "Gambar 6" => "https://shopmoisturetesters.com/service/",
    "Gambar 7" => "https://northridgeautowrecking.com/auto"
];

echo "=== PENGUJIAN 6 TAUTAN JUDOL TERSELUBUNG (CLOAKED / HIJACKED DOMAINS) ===" . PHP_EOL . PHP_EOL;

$allDetected = true;
foreach ($urls as $label => $u) {
    $res = $orchestrator->analyzeLink($u)->toArray();
    $isRed = ($res['alertClass'] === 'red' && ($res['risk_score'] ?? 0) >= 85);
    if (!$isRed) {
        $allDetected = false;
    }
    echo ($isRed ? "✅ TERDETEKSI BERBAHAYA" : "❌ GAGAL") . " [{$label}]: {$u}" . PHP_EOL;
    echo "   Status     : {$res['statusAlert']} | Tingkat Bahaya: {$res['risk_score']}% | Warna: {$res['alertClass']}" . PHP_EOL;
    echo "   Judul      : {$res['title']}" . PHP_EOL;
    echo "   Teknik     : " . implode(', ', $res['techniques'] ?? []) . PHP_EOL;
    foreach (array_slice($res['findings'] ?? [], 0, 3) as $f) {
        echo "   - Bukti    : [{$f['label']}] {$f['detail']}" . PHP_EOL;
    }
    echo PHP_EOL;
}

exit($allDetected ? 0 : 1);

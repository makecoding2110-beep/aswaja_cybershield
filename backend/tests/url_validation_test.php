<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\UrlValidationService;

echo "=== UNIT TEST: URL VALIDATION & NORMALIZATION SERVICE ===\n\n";

$service = new UrlValidationService();
$passed = 0;
$failed = 0;

function runTest(string $description, callable $fn, int &$passed, int &$failed): void
{
    try {
        $result = $fn();
        if ($result === true) {
            echo "✅ PASS: {$description}\n";
            $passed++;
        } else {
            echo "❌ FAIL: {$description}\n";
            $failed++;
        }
    } catch (\Throwable $e) {
        echo "❌ ERROR: {$description} -> " . $e->getMessage() . "\n";
        $failed++;
    }
}

// 1. Normalisasi Skema jika tidak ada
runTest("Normalisasi otomatis tanpa skema (menambahkan https://)", function () use ($service) {
    $norm = $service->normalize("cuan-trading.com/daftar");
    return $norm === "https://cuan-trading.com/daftar";
}, $passed, $failed);

// 2. Proteksi SSRF terhadap localhost
runTest("Proteksi SSRF memblokir localhost", function () use ($service) {
    try {
        $service->normalize("http://localhost:8000/admin");
        return false;
    } catch (\InvalidArgumentException $e) {
        return str_contains($e->getMessage(), 'host lokal/internal');
    }
}, $passed, $failed);

// 3. Proteksi SSRF terhadap 127.0.0.1
runTest("Proteksi SSRF memblokir IP loopback 127.0.0.1", function () use ($service) {
    try {
        $service->normalize("http://127.0.0.1/evil");
        return false;
    } catch (\InvalidArgumentException $e) {
        return true;
    }
}, $passed, $failed);

// 4. Deteksi Typosquatting OJK
runTest("Deteksi typosquatting/pencatutan OJK (ojk-investasi.xyz)", function () use ($service) {
    $dto = $service->analyze("https://ojk-investasi.xyz/claim");
    $hasTyposquatting = false;
    foreach ($dto->indicators as $ind) {
        if ($ind['name'] === 'typosquatting_detection' && $ind['severity'] === 'critical') {
            $hasTyposquatting = true;
        }
    }
    return $dto->hasTyposquatting && $hasTyposquatting && $dto->isSuspiciousTld;
}, $passed, $failed);

// 5. Domain Resmi OJK tidak boleh dianggap typosquatting
runTest("Domain resmi OJK (ojk.go.id) terverifikasi bukan typosquatting", function () use ($service) {
    $dto = $service->analyze("https://ojk.go.id/id/berita-dan-kegiatan");
    return !$dto->hasTyposquatting && !$dto->isSuspiciousTld;
}, $passed, $failed);

// 6. Deteksi Huruf Berulang & Tahun 2026 (investasii2026.com)
runTest("Deteksi huruf berulang dan tahun kekinian (investasii2026.com)", function () use ($service) {
    $dto = $service->analyze("https://investasii2026.com");
    $names = array_column($dto->indicators, 'name');
    return in_array('repetitive_characters', $names, true) && in_array('recency_deception_year', $names, true);
}, $passed, $failed);

// 7. Deteksi Angka Hoki Judol & TLD Berisiko (slot-gacor777.top)
runTest("Deteksi angka keberuntungan judol 777 & TLD .top (slot-gacor777.top)", function () use ($service) {
    $dto = $service->analyze("https://slot-gacor777.top");
    $names = array_column($dto->indicators, 'name');
    return in_array('lucky_gambling_numbers', $names, true) && $dto->isSuspiciousTld;
}, $passed, $failed);

// 8. Deteksi Deretan Angka Acak Panjang (investasi-bonus-827391.com)
runTest("Deteksi deretan angka acak panjang pada domain (investasi-bonus-827391.com)", function () use ($service) {
    $dto = $service->analyze("https://investasi-bonus-827391.com");
    $names = array_column($dto->indicators, 'name');
    return in_array('random_digits_domain', $names, true);
}, $passed, $failed);

// 9. Deteksi Penggunaan Alamat IP Langsung
runTest("Deteksi penggunaan alamat IP langsung (http://185.220.101.5/slot)", function () use ($service) {
    $dto = $service->analyze("http://185.220.101.5/slot");
    return $dto->isDirectIp;
}, $passed, $failed);

// 10. Deteksi URL Shortener
runTest("Deteksi pemendek URL bit.ly", function () use ($service) {
    $dto = $service->analyze("https://bit.ly/cuan-instan");
    return $dto->isShortener;
}, $passed, $failed);

// 11. Deteksi Parameter Referral & Deposit Path
runTest("Deteksi parameter referral dan deposit path", function () use ($service) {
    $dto = $service->analyze("https://cuan-trading-vip88.vip/deposit-slot?ref=agen123");
    $names = array_column($dto->indicators, 'name');
    return in_array('referral_agent_param', $names, true) && in_array('deposit_promo_path', $names, true);
}, $passed, $failed);

// 12. Domain legal dengan prefix www (tidak boleh dianggap repetitive_characters / typosquatting)
runTest("Domain legal dengan prefix www (www.dicoding.com) tidak boleh terdeteksi bahaya", function () use ($service) {
    $dto = $service->analyze("https://www.dicoding.com/blog/flowchart-adalah/");
    $names = array_column($dto->indicators, 'name');
    return !in_array('repetitive_characters', $names, true) && !$dto->hasTyposquatting;
}, $passed, $failed);

echo "\nHASIL PENGUJIAN: {$passed} PASS, {$failed} FAIL\n";
exit($failed > 0 ? 1 : 0);


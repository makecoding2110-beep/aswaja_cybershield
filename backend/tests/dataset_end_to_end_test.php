<?php

/**
 * Pengujian Terpadu End-to-End dengan Dataset Nyata/Spesifikasi (Tahap 6)
 * Sesuai Dokumen Spesifikasi Bagian 3, Bagian 6, Bagian 7, dan Bagian 8
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\AiOrchestratorService;
use App\DTO\AnalysisResultDTO;

echo "=== PENGUJIAN END-TO-END DATASET TAUTAN (TAHAP 6) ===\n\n";

$orchestrator = app(AiOrchestratorService::class);
$passed = 0;
$failed = 0;

function assertCondition(bool $cond, string $message) {
    global $passed, $failed;
    if ($cond) {
        echo "✅ PASS: $message\n";
        $passed++;
    } else {
        echo "❌ FAIL: $message\n";
        $failed++;
    }
}

// -----------------------------------------------------------------------------
// Skenario 1: Dataset Tautan Legal Resmi (ojk.go.id)
// -----------------------------------------------------------------------------
echo "[1] Menguji Tautan Resmi Regulator (https://ojk.go.id)...\n";
$res1 = $orchestrator->analyzeLink('https://ojk.go.id');
assertCondition($res1 instanceof AnalysisResultDTO, "Hasil analisis bertipe AnalysisResultDTO");
assertCondition($res1->category === 'normal', "Kategori resmi OJK adalah 'normal' (terverifikasi aman)");
assertCondition($res1->alertClass === 'emerald', "Warna indikator adalah 'emerald'");
assertCondition($res1->score >= 80, "Tingkat keaslian/keamanan tinggi (>= 80): score = {$res1->score}");
assertCondition(str_contains(strtolower($res1->title), 'aman') || str_contains(strtolower($res1->title), 'normal'), "Judul menampilkan status aman/normal");

// -----------------------------------------------------------------------------
// Skenario 2: Dataset Typosquatting Menyerupai Lembaga Resmi (ojk-investasi.xyz)
// -----------------------------------------------------------------------------
echo "\n[2] Menguji Typosquatting Lembaga Resmi (https://ojk-investasi.xyz)...\n";
$res2 = $orchestrator->analyzeLink('https://ojk-investasi.xyz');
assertCondition($res2->alertClass === 'red' || $res2->alertClass === 'amber', "Typosquatting OJK diberi status peringatan bahaya (red/amber)");
assertCondition($res2->score <= 60, "Tingkat keamanan rendah (<= 60): score = {$res2->score}");
$hasTyposquattingEvidence = false;
foreach ($res2->findings as $f) {
    if (str_contains(strtolower($f['detail'] ?? ''), 'typosquatting') || str_contains(strtolower($f['detail'] ?? ''), 'ojk')) {
        $hasTyposquattingEvidence = true;
        break;
    }
}
assertCondition($hasTyposquattingEvidence, "Temuan memuat bukti deteksi typosquatting/pencatutan OJK");

// -----------------------------------------------------------------------------
// Skenario 3: Dataset Tautan Judi Online Terang-terangan (https://slot-gacor777.top)
// -----------------------------------------------------------------------------
echo "\n[3] Menguji Tautan Judi Online Kuat (https://slot-gacor777.top)...\n";
$res3 = $orchestrator->analyzeLink('https://slot-gacor777.top');
assertCondition($res3->category === 'terindikasi_judol', "Kategori terdeteksi sebagai 'terindikasi_judol'");
assertCondition($res3->alertClass === 'red', "Warna peringatan adalah 'red'");
assertCondition($res3->score <= 35, "Tingkat keamanan sangat rendah (<= 35): score = {$res3->score}");
$hasSlotEvidence = false;
foreach ($res3->findings as $f) {
    if (str_contains(strtolower($f['detail'] ?? ''), 'slot') || str_contains(strtolower($f['detail'] ?? ''), '777') || str_contains(strtolower($f['detail'] ?? ''), 'top')) {
        $hasSlotEvidence = true;
        break;
    }
}
assertCondition($hasSlotEvidence, "Temuan menyertakan bukti pola slot / 777 / TLD .top");

// -----------------------------------------------------------------------------
// Skenario 4: Dataset Tautan Investasi Berulang & Tahun Kekinian (investasii2026.com)
// -----------------------------------------------------------------------------
echo "\n[4] Menguji Tautan Investasi Anomali Karakter (https://investasii2026.com/auto-profit)...\n";
$res4 = $orchestrator->analyzeLink('https://investasii2026.com/auto-profit');
assertCondition($res4->alertClass === 'red' || $res4->alertClass === 'amber', "Pola manipulasi karakter & auto profit menghasilkan peringatan (red/amber)");
$hasRepetitiveOrYearEvidence = false;
foreach ($res4->findings as $f) {
    if (str_contains(strtolower($f['detail'] ?? ''), 'berulang') || str_contains(strtolower($f['detail'] ?? ''), '2026') || str_contains(strtolower($f['detail'] ?? ''), 'investasii')) {
        $hasRepetitiveOrYearEvidence = true;
        break;
    }
}
assertCondition($hasRepetitiveOrYearEvidence, "Temuan menyertakan bukti huruf berulang atau tahun kekinian");

// -----------------------------------------------------------------------------
// Skenario 5: Dataset Alamat IP Langsung Mengarah ke Judol (http://185.220.101.5/slot)
// -----------------------------------------------------------------------------
echo "\n[5] Menguji Alamat IP Langsung (http://185.220.101.5/slot)...\n";
$res5 = $orchestrator->analyzeLink('http://185.220.101.5/slot');
assertCondition($res5->alertClass === 'red', "Alamat IP langsung dengan path slot diklasifikasikan 'red'");
$hasIpEvidence = false;
foreach ($res5->findings as $f) {
    if (str_contains(strtolower($f['detail'] ?? ''), 'ip') || str_contains(strtolower($f['detail'] ?? ''), '185.220.101.5')) {
        $hasIpEvidence = true;
        break;
    }
}
assertCondition($hasIpEvidence, "Temuan memuat bukti direct IP usage");

// -----------------------------------------------------------------------------
// Skenario 6: Dataset Kombinasi Angka Acak & Kedok Bonus (investasi-bonus-827391.com)
// -----------------------------------------------------------------------------
echo "\n[6] Menguji Deretan Angka Acak Panjang (https://investasi-bonus-827391.com)...\n";
$res6 = $orchestrator->analyzeLink('https://investasi-bonus-827391.com');
$hasRandomDigitEvidence = false;
foreach ($res6->findings as $f) {
    if (str_contains(strtolower($f['detail'] ?? ''), 'angka acak') || str_contains(strtolower($f['detail'] ?? ''), '827391')) {
        $hasRandomDigitEvidence = true;
        break;
    }
}
assertCondition($hasRandomDigitEvidence, "Temuan menyertakan bukti deretan angka acak panjang pada domain");

// -----------------------------------------------------------------------------
// Skenario 7: Verifikasi Riwayat Audit Tersimpan di Database/In-Memory
// -----------------------------------------------------------------------------
echo "\n[7] Menguji Verifikasi Penyimpanan Riwayat Audit...\n";
$scanHistoryService = app(\App\Services\ScanHistoryService::class);
$recent = $scanHistoryService->getRecentScans(10);
assertCondition(count($recent) >= 6, "Setiap URL yang diuji otomatis tersimpan dalam riwayat audit (total: " . count($recent) . ")");
$lastScan = $recent[0] ?? null;
assertCondition($lastScan !== null && !empty($lastScan['id']), "Riwayat audit memiliki ID terstruktur ({$lastScan['id']})");
assertCondition(isset($lastScan['suricata_result']) && isset($lastScan['gemini_result']) && isset($lastScan['deepseek_result']), "Riwayat audit memisahkan hasil Suricata, Gemini, dan DeepSeek");

// -----------------------------------------------------------------------------
// Skenario 8: Uji Tiga Tingkat Pencocokan - Tingkat KONTEKSTUAL Mandiri (Anti-False-Positive)
// -----------------------------------------------------------------------------
echo "\n[8] Menguji Kata Kunci KONTEKSTUAL Mandiri (https://ensiklopedia-mitologi-zeus-thor.com)...\n";
$res8 = $orchestrator->analyzeLink('https://ensiklopedia-mitologi-zeus-thor.com');
assertCondition($res8->alertClass === 'emerald', "Website biasa dengan kata 'zeus'/'thor' TIDAK salah dituduh sebagai Judol (alertClass = {$res8->alertClass})");
assertCondition($res8->category !== 'terindikasi_judol', "Kategori bukan terindikasi_judol (category = {$res8->category})");

// -----------------------------------------------------------------------------
// Skenario 9: Uji Tiga Tingkat Pencocokan - Tingkat MENENGAH Mandiri (Perlu Indikator Pendukung)
// -----------------------------------------------------------------------------
echo "\n[9] Menguji Kata Kunci MENENGAH Mandiri (https://info-undian-jackpot.com)...\n";
$res9 = $orchestrator->analyzeLink('https://info-undian-jackpot.com');
assertCondition($res9->alertClass === 'amber', "Kata tingkat menengah ('jackpot') tanpa indikator pendukung berstatus 'amber' / perlu verifikasi (alertClass = {$res9->alertClass})");
assertCondition($res9->category === 'mencurigakan', "Kategori adalah 'mencurigakan' (Perlu Indikator Pendukung)");

// -----------------------------------------------------------------------------
// Skenario 10: Uji Tiga Tingkat Pencocokan - Kombinasi Kontekstual + Menengah + Pendukung
// -----------------------------------------------------------------------------
echo "\n[10] Menguji Kata Kontekstual + Menengah + Indikator Pendukung (https://zeus-maxwin888.top/deposit)...\n";
$res10 = $orchestrator->analyzeLink('https://zeus-maxwin888.top/deposit');
assertCondition($res10->category === 'terindikasi_judol', "Kombinasi zeus + maxwin + 888 + .top + /deposit terkonfirmasi 'terindikasi_judol'");
assertCondition($res10->alertClass === 'red', "Warna peringatan adalah 'red'");

echo "\n=============================================\n";
echo "HASIL PENGUJIAN END-TO-END DATASET: $passed PASS, $failed FAIL\n";
echo "=============================================\n";

if ($failed > 0) {
    exit(1);
}


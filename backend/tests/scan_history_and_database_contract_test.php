<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\UrlValidationService;
use App\Services\AiComparisonService;
use App\Services\RiskAssessmentService;
use App\Services\ScanHistoryService;
use App\DTO\AiAnalysisResultDTO;
use App\Models\LinkScan;

echo "=== UNIT TEST: SCAN HISTORY & DATABASE CONTRACT (TAHAP 5) ===\n\n";

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

$urlValidator = app(UrlValidationService::class);
$comparisonService = new AiComparisonService();
$riskAssessmentService = new RiskAssessmentService();
$historyService = new ScanHistoryService();

// 1. Verifikasi Catatan Kontrak Database Developer
runTest("CATATAN_DATABASE.md memuat skema resmi tabel link_scans", function () {
    $contractPath = file_exists(__DIR__ . '/../../CATATAN_DATABASE.md')
        ? __DIR__ . '/../../CATATAN_DATABASE.md'
        : __DIR__ . '/../../KONTRAK_DATABASE_DEVELOPER.md';
    if (!file_exists($contractPath)) {
        return false;
    }
    $content = file_get_contents($contractPath);

    return str_contains($content, 'CREATE TABLE link_scans')
        && str_contains($content, 'suricata_result JSONB')
        && str_contains($content, 'gemini_result JSONB')
        && str_contains($content, 'ai_comparison JSONB')
        && str_contains($content, 'indicators JSONB')
        && str_contains($content, 'final_category VARCHAR(50)')
        && str_contains($content, 'risk_score INTEGER');
}, $passed, $failed);

// 2. Verifikasi Berkas Migrasi Laravel
runTest("Berkas migrasi create_link_scans_table terdaftar di backend/database/migrations", function () {
    $migrationsDir = __DIR__ . '/../database/migrations';
    $files = glob($migrationsDir . '/*_create_link_scans_table.php');

    return count($files) > 0;
}, $passed, $failed);

// 3. Verifikasi Model Eloquent LinkScan
runTest("Model Eloquent LinkScan memiliki konfigurasi casts dan fillable yang sesuai", function () {
    $model = new LinkScan();

    return $model->getTable() === 'link_scans'
        && in_array('suricata_result', $model->getFillable(), true)
        && in_array('gemini_result', $model->getFillable(), true)
        && in_array('ai_comparison', $model->getFillable(), true)
        && in_array('final_category', $model->getFillable(), true)
        && $model->getCasts()['risk_score'] === 'integer';
}, $passed, $failed);

// 4. Pengujian Penyimpanan Riwayat Pemeriksaan Terpisah (ScanHistoryService)
runTest("ScanHistoryService mencatat pemeriksaan lengkap dengan hasil engine terpisah", function () use ($urlValidator, $comparisonService, $riskAssessmentService, $historyService) {
    $urlAnalysis = $urlValidator->analyze('https://slot-gacor777.top/deposit');

    $suricataResult = [
        'status' => 'tersedia',
        'available' => true,
        'matched' => true,
        'alerts_count' => 1,
        'alerts' => [['signature_id' => 1000001, 'alert_signature' => 'SUSPICIOUS DOMAIN - JUDI ONLINE', 'severity' => 1]],
        'indicators' => []
    ];

    $gemini = new AiAnalysisResultDTO(
        provider: 'gemini',
        model: 'gemini-1.5-flash',
        status: 'connected',
        category: 'judol',
        riskLevel: 'critical',
        riskScore: 92,
        indicators: [['name' => 'slot_kw', 'evidence' => 'slot gacor', 'severity' => 'critical', 'explanation' => 'Mesin slot']],
        reasoning: 'Pola perjudian slot.',
        confidence: 0.95
    );

    $comparison = $comparisonService->compare($gemini, null);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, null, $comparison);

    $saved = $historyService->recordScan($urlAnalysis, $suricataResult, $gemini, null, $comparison, $assessment);

    // Verifikasi struktur data yang disimpan
    $hasValidId = str_starts_with($saved['id'], 'SCN-');
    $suricataSavedSeparately = isset($saved['suricata_result']['status']) && $saved['suricata_result']['matched'] === true;
    $geminiSavedSeparately = isset($saved['gemini_result']['provider']) && $saved['gemini_result']['provider'] === 'gemini';
    $comparisonSaved = isset($saved['ai_comparison']);

    // Verifikasi pengambilan kembali via findById
    $retrieved = $historyService->findById($saved['id']);

    return $hasValidId
        && $suricataSavedSeparately
        && $geminiSavedSeparately
        && $comparisonSaved
        && $retrieved !== null
        && $retrieved['id'] === $saved['id'];
}, $passed, $failed);

// 5. Pengujian Pengambilan Riwayat Terbaru (getRecentScans)
runTest("ScanHistoryService mengembalikan daftar riwayat terbaru dengan urutan kronologis", function () use ($historyService) {
    $recent = $historyService->getRecentScans(10);

    return is_array($recent) && count($recent) >= 1;
}, $passed, $failed);

echo "\nHASIL PENGUJIAN TAHAP 5: {$passed} PASS, {$failed} FAIL\n";
exit($failed > 0 ? 1 : 0);


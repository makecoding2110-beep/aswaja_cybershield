<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\UrlValidationService;
use App\Services\AiComparisonService;
use App\Services\RiskAssessmentService;
use App\DTO\AiAnalysisResultDTO;

echo "=== UNIT TEST: AI COMPARISON & RISK ASSESSMENT ENGINE (TAHAP 4) ===\n\n";

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

// Helper untuk membuat mock AI result
function makeAiResult(string $provider, ?string $category, int $riskScore, array $indicators = [], string $status = 'connected'): AiAnalysisResultDTO {
    return new AiAnalysisResultDTO(
        provider: $provider,
        model: $provider . '-model',
        status: $status,
        category: $category,
        riskLevel: $riskScore >= 85 ? 'critical' : ($riskScore >= 60 ? 'high' : 'low'),
        riskScore: $riskScore,
        indicators: $indicators,
        reasoning: "Analisis {$provider} untuk kategori {$category}",
        confidence: 0.95
    );
}

// 1. Skenario Matriks 1: Suricata Alert + Gemini Judol + DeepSeek Judol -> Terindikasi Judi Online
runTest("Matriks 1: Suricata Alert + Gemini Judol + DeepSeek Judol => Terindikasi Judi Online (Kritis/Tinggi)", function () use ($urlValidator, $comparisonService, $riskAssessmentService) {
    $urlAnalysis = $urlValidator->analyze('https://slot-gacor777.top/deposit');

    $suricataResult = [
        'available' => true,
        'matched' => true,
        'alerts' => [['sid' => 1000001, 'msg' => 'SUSPICIOUS DOMAIN - JUDI ONLINE', 'priority' => 1]],
        'indicators' => [['name' => 'suricata_alert', 'evidence' => 'SID 1000001', 'severity' => 'critical', 'explanation' => 'Mesin slot']]
    ];

    $gemini = makeAiResult('gemini', 'judol', 92, [
        ['name' => 'keyword_slot', 'evidence' => 'slot gacor', 'severity' => 'critical', 'explanation' => 'Istilah judi mesin slot']
    ]);

    $deepseek = makeAiResult('deepseek', 'judol', 95, [
        ['name' => 'deposit_flow', 'evidence' => 'deposit dana 10k', 'severity' => 'critical', 'explanation' => 'Pola setoran taruhan']
    ]);

    $comparison = $comparisonService->compare($gemini, $deepseek);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, $deepseek, $comparison);

    return $comparison->isMatch
        && $assessment->finalCategory === 'terindikasi_judol'
        && $assessment->alertClass === 'red'
        && in_array($assessment->riskLevel, ['kritis', 'tinggi'], true)
        && $assessment->riskScore >= 85;
}, $passed, $failed);

// 2. Skenario Matriks 2: Suricata Alert + Gemini Investasi + DeepSeek Investasi -> Terindikasi Investasi Menyesatkan
runTest("Matriks 2: Suricata Alert + Gemini Investasi + DeepSeek Investasi => Terindikasi Investasi Menyesatkan", function () use ($urlValidator, $comparisonService, $riskAssessmentService) {
    $urlAnalysis = $urlValidator->analyze('https://investasi-cuan2026.com/robot-trading');

    $suricataResult = [
        'available' => true,
        'matched' => true,
        'alerts' => [['sid' => 1000006, 'msg' => 'SUSPICIOUS PROMO - ROBOT TRADING BODONG', 'priority' => 1]],
        'indicators' => []
    ];

    $gemini = makeAiResult('gemini', 'investasi_menyesatkan', 88, [
        ['name' => 'unrealistic_profit', 'evidence' => 'cuan 20% harian', 'severity' => 'high', 'explanation' => 'Janji profit tidak masuk akal']
    ]);

    $deepseek = makeAiResult('deepseek', 'investasi_menyesatkan', 85, [
        ['name' => 'ponzi_scheme', 'evidence' => 'bonus referral berjenjang', 'severity' => 'high', 'explanation' => 'Skema piramida penipuan']
    ]);

    $comparison = $comparisonService->compare($gemini, $deepseek);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, $deepseek, $comparison);

    return $comparison->isMatch
        && $assessment->finalCategory === 'terindikasi_investasi'
        && $assessment->alertClass === 'red'
        && in_array($assessment->riskLevel, ['kritis', 'tinggi'], true);
}, $passed, $failed);

// 3. Skenario Matriks 3: Suricata Normal + Gemini Judol + DeepSeek Judol -> Perlu Pemeriksaan Bukti Tambahan
runTest("Matriks 3: Suricata Normal + Gemini Judol + DeepSeek Judol => Mencurigakan / Perlu Pemeriksaan Bukti Tambahan", function () use ($urlValidator, $comparisonService, $riskAssessmentService) {
    $urlAnalysis = $urlValidator->analyze('https://portal-game-taruhan.com');

    $suricataResult = ['available' => true, 'matched' => false, 'alerts' => [], 'indicators' => []];

    $gemini = makeAiResult('gemini', 'judol', 80);
    $deepseek = makeAiResult('deepseek', 'judol', 82);

    $comparison = $comparisonService->compare($gemini, $deepseek);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, $deepseek, $comparison);

    return $assessment->finalCategory === 'mencurigakan'
        && $assessment->decisionSource === 'dual_ai_only';
}, $passed, $failed);

// 4. Skenario Matriks 4: Suricata Alert + AI Normal + AI Normal -> Mencurigakan (Anomali Teknis)
runTest("Matriks 4: Suricata Alert + AI Normal + AI Normal => Mencurigakan / Perlu Pemeriksaan Lanjut", function () use ($urlValidator, $comparisonService, $riskAssessmentService) {
    $urlAnalysis = $urlValidator->analyze('https://contoh-domain-baru.vip');

    $suricataResult = [
        'available' => true,
        'matched' => true,
        'alerts' => [['sid' => 1000005, 'msg' => 'SUSPICIOUS TLD - FAST FLUX', 'priority' => 2]],
        'indicators' => []
    ];

    $gemini = makeAiResult('gemini', 'normal', 15);
    $deepseek = makeAiResult('deepseek', 'normal', 10);

    $comparison = $comparisonService->compare($gemini, $deepseek);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, $deepseek, $comparison);

    return $assessment->finalCategory === 'mencurigakan'
        && $assessment->decisionSource === 'suricata_discrepancy';
}, $passed, $failed);

// 5. Skenario Matriks 5: Suricata Normal + Gemini Normal + DeepSeek Normal -> Normal
runTest("Matriks 5: Suricata Normal + Gemini Normal + DeepSeek Normal => Normal (Aman Terverifikasi)", function () use ($urlValidator, $comparisonService, $riskAssessmentService) {
    $urlAnalysis = $urlValidator->analyze('https://ojk.go.id/id/berita-dan-kegiatan');

    $suricataResult = ['available' => true, 'matched' => false, 'alerts' => [], 'indicators' => []];

    $gemini = makeAiResult('gemini', 'normal', 5);
    $deepseek = makeAiResult('deepseek', 'normal', 5);

    $comparison = $comparisonService->compare($gemini, $deepseek);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, $deepseek, $comparison);

    return $assessment->finalCategory === 'normal'
        && $assessment->alertClass === 'emerald'
        && $assessment->riskLevel === 'rendah'
        && $assessment->riskScore <= 25;
}, $passed, $failed);

// 6. Skenario Matriks 6: Suricata Alert + Gemini Judol + DeepSeek Investasi -> Belum Dapat Disimpulkan
runTest("Matriks 6: Suricata Alert + Gemini Judol vs DeepSeek Investasi (Disparitas AI) => Belum Dapat Disimpulkan", function () use ($urlValidator, $comparisonService, $riskAssessmentService) {
    $urlAnalysis = $urlValidator->analyze('https://cuan-trading-vip88.vip');

    $suricataResult = ['available' => true, 'matched' => true, 'alerts' => [], 'indicators' => []];

    $gemini = makeAiResult('gemini', 'judol', 85);
    $deepseek = makeAiResult('deepseek', 'investasi_menyesatkan', 80);

    $comparison = $comparisonService->compare($gemini, $deepseek);
    $assessment = $riskAssessmentService->evaluate($urlAnalysis, $suricataResult, $gemini, $deepseek, $comparison);

    return $assessment->finalCategory === 'belum_dapat_disimpulkan'
        && $comparison->hasDiscrepancy === true
        && $assessment->decisionSource === 'ai_divergence';
}, $passed, $failed);

// 7. Pengujian Deduplikasi Bukti Gabungan (Combined Indicators)
runTest("Deduplikasi bukti: Evidence identik dari Gemini dan DeepSeek tidak digandakan", function () use ($comparisonService) {
    $gemini = makeAiResult('gemini', 'judol', 90, [
        ['name' => 'slot_kw', 'evidence' => 'slot gacor 777', 'severity' => 'critical', 'explanation' => 'Kata kunci mesin slot']
    ]);

    $deepseek = makeAiResult('deepseek', 'judol', 90, [
        ['name' => 'slot_kw', 'evidence' => 'slot gacor 777', 'severity' => 'critical', 'explanation' => 'Penalaran taruhan slot']
    ]);

    $comparison = $comparisonService->compare($gemini, $deepseek);

    // Harus terdeduplikasi menjadi 1 bukti utama
    return count($comparison->combinedIndicators) === 1;
}, $passed, $failed);

echo "\nHASIL PENGUJIAN TAHAP 4: {$passed} PASS, {$failed} FAIL\n";
exit($failed > 0 ? 1 : 0);


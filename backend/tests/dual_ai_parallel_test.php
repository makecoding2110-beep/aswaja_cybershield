<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GeminiService;
use App\Services\AiOrchestratorService;
use App\DTO\AiAnalysisResultDTO;
use Illuminate\Support\Facades\Http;

echo "=== UNIT TEST: AI ENGINE & ORCHESTRATION (GOOGLE GEMINI AI) ===\n\n";

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

$geminiService = app(GeminiService::class);
$orchestrator = app(AiOrchestratorService::class);

// 1. Validasi DTO Unconfigured (Anti-Data Tiruan / Aturan 7 & 8)
runTest("Gemini DTO Unconfigured mengembalikan status unconfigured tanpa data palsu", function () use ($geminiService) {
    $dto = AiAnalysisResultDTO::unconfigured('gemini', $geminiService->getModel());
    return $dto->status === 'unconfigured' && $dto->category === null && $dto->riskScore === 0 && !$dto->isSuccess();
}, $passed, $failed);

// 2. Parser Output JSON Gemini sesuai Spesifikasi Bagian 5.4
runTest("GeminiService mem-parsing dan memvalidasi JSON terstruktur (Judol)", function () use ($geminiService) {
    $rawGeminiJson = json_encode([
        'candidates' => [
            [
                'content' => [
                    'parts' => [
                        [
                            'text' => json_encode([
                                'category' => 'judol',
                                'risk_level' => 'critical',
                                'risk_score' => 95,
                                'indicators' => [
                                    [
                                        'name' => 'keyword_detection',
                                        'evidence' => 'slot gacor deposit dana',
                                        'severity' => 'critical',
                                        'explanation' => 'Mengandung istilah promosi perjudian mesin slot'
                                    ]
                                ],
                                'reasoning' => 'Tautan mengarahkan ke formulir deposit taruhan mesin slot.',
                                'confidence' => 0.96
                            ])
                        ]
                    ]
                ]
            ]
        ]
    ]);

    $dto = $geminiService->parseResponse($rawGeminiJson, 200, 'https://slot-gacor777.top');
    return $dto->isSuccess()
        && $dto->category === 'judol'
        && $dto->riskLevel === 'critical'
        && $dto->riskScore === 95
        && count($dto->indicators) === 1
        && $dto->confidence === 0.96;
}, $passed, $failed);

// 3. Sanitasi Markdown Triple Backticks pada respon AI
runTest("Sanitasi keluaran AI jika membungkus JSON dengan ```json markdown", function () use ($geminiService) {
    $rawWithMarkdown = json_encode([
        'candidates' => [
            [
                'content' => [
                    'parts' => [
                        [
                            'text' => "```json\n" . json_encode([
                                'category' => 'normal',
                                'risk_level' => 'low',
                                'risk_score' => 5,
                                'indicators' => [],
                                'reasoning' => 'Portal resmi pemerintah.',
                                'confidence' => 0.99
                            ]) . "\n```"
                        ]
                    ]
                ]
            ]
        ]
    ]);

    $dto = $geminiService->parseResponse($rawWithMarkdown, 200, 'https://ojk.go.id');
    return $dto->isSuccess() && $dto->category === 'normal' && $dto->riskScore === 5;
}, $passed, $failed);

// 4. Eksekusi Analisis Gemini via Mock
runTest("Eksekusi Analisis Gemini via HTTP Mock", function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            [
                                'text' => json_encode([
                                    'category' => 'judol',
                                    'risk_level' => 'critical',
                                    'risk_score' => 90,
                                    'indicators' => [
                                        [
                                            'name' => 'gemini_keyword',
                                            'evidence' => 'slot 777',
                                            'severity' => 'critical',
                                            'explanation' => 'Promosi mesin slot'
                                        ]
                                    ],
                                    'reasoning' => 'Gemini mendeteksi kata kunci judol.',
                                    'confidence' => 0.95
                                ])
                            ]
                        ]
                    ]
                ]
            ]
        ], 200)
    ]);

    config(['services.gemini.api_key' => 'fake-gemini-key']);

    $geminiSvc = new GeminiService();
    $orch = new AiOrchestratorService($geminiSvc);

    $results = $orch->executeParallelAi('https://slot-gacor777.top');
    $gemini = $results['gemini'];

    return $gemini->isSuccess()
        && $gemini->category === 'judol'
        && $gemini->provider === 'gemini';
}, $passed, $failed);

// 5. Integrasi Hasil Analisis Gemini ke AnalyzeLink
runTest("Orkestrator menjalankan analisis Gemini dan mendeteksi ancaman", function () {
    $geminiSvc = new GeminiService();
    $orch = new AiOrchestratorService($geminiSvc);

    $result = $orch->analyzeLink('https://slot-gacor777.top');

    return $result->alertClass === 'red'
        && count($result->findings) >= 2;
}, $passed, $failed);

echo "\nHASIL PENGUJIAN AI: {$passed} PASS, {$failed} FAIL\n";
exit($failed > 0 ? 1 : 0);


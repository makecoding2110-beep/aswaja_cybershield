<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GeminiService;
use App\Services\DeepSeekService;
use App\Services\AiOrchestratorService;
use App\DTO\AiAnalysisResultDTO;
use Illuminate\Support\Facades\Http;

echo "=== UNIT TEST: DUAL AI STANDARDIZATION & PARALLEL PROCESSING (TAHAP 2) ===\n\n";

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
$deepSeekService = app(DeepSeekService::class);
$orchestrator = app(AiOrchestratorService::class);

// 1. Validasi DTO Unconfigured (Anti-Data Tiruan / Aturan 7 & 8)
runTest("Gemini DTO Unconfigured mengembalikan status unconfigured tanpa data palsu", function () use ($geminiService) {
    $dto = AiAnalysisResultDTO::unconfigured('gemini', $geminiService->getModel());
    return $dto->status === 'unconfigured' && $dto->category === null && $dto->riskScore === 0 && !$dto->isSuccess();
}, $passed, $failed);

runTest("DeepSeek DTO Unconfigured mengembalikan status unconfigured tanpa data palsu", function () use ($deepSeekService) {
    $dto = AiAnalysisResultDTO::unconfigured('deepseek', $deepSeekService->getModel());
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

// 3. Parser Output JSON DeepSeek sesuai Spesifikasi Bagian 5.4
runTest("DeepSeekService mem-parsing dan memvalidasi JSON terstruktur (Investasi Menyesatkan)", function () use ($deepSeekService) {
    $rawDeepSeekJson = json_encode([
        'choices' => [
            [
                'message' => [
                    'content' => json_encode([
                        'category' => 'investasi_menyesatkan',
                        'risk_level' => 'high',
                        'risk_score' => 88,
                        'indicators' => [
                            [
                                'name' => 'unrealistic_return',
                                'evidence' => 'profit pasti 20% mingguan',
                                'severity' => 'high',
                                'explanation' => 'Klaim return pasti tanpa risiko melanggar kaidah pasar finansial'
                            ]
                        ],
                        'reasoning' => 'Ditemukan skema ponzi berkedok arisan digital dengan jaminan keuntungan pasti.',
                        'confidence' => 0.92
                    ])
                ]
            ]
        ]
    ]);

    $dto = $deepSeekService->parseResponse($rawDeepSeekJson, 200, 'https://investasi-cuan2026.com');
    return $dto->isSuccess()
        && $dto->category === 'investasi_menyesatkan'
        && $dto->riskLevel === 'high'
        && $dto->riskScore === 88
        && count($dto->indicators) === 1
        && $dto->confidence === 0.92;
}, $passed, $failed);

// 4. Sanitasi Markdown Triple Backticks pada respon AI
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

// 5. Eksekusi Paralel Dual AI via Http::pool
runTest("Eksekusi Paralel Http::pool memanggil Gemini & DeepSeek secara serentak", function () use ($orchestrator) {
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
        ], 200),
        'api.deepseek.com/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'category' => 'judol',
                            'risk_level' => 'critical',
                            'risk_score' => 92,
                            'indicators' => [
                                [
                                    'name' => 'deepseek_reasoning',
                                    'evidence' => 'pola taruhan berulang',
                                    'severity' => 'critical',
                                    'explanation' => 'Pola penarikan dana judol'
                                ]
                            ],
                            'reasoning' => 'DeepSeek mengidentifikasi indikasi perjudian.',
                            'confidence' => 0.94
                        ])
                    ]
                ]
            ]
        ], 200)
    ]);

    // Simulasikan API key sementara untuk pengujian paralel
    config([
        'services.gemini.api_key' => 'fake-gemini-key',
        'services.deepseek.api_key' => 'fake-deepseek-key'
    ]);

    $geminiSvc = new GeminiService();
    $deepSeekSvc = new DeepSeekService();
    $orch = new AiOrchestratorService($geminiSvc, $deepSeekSvc);

    $results = $orch->executeParallelAi('https://slot-gacor777.top');

    $gemini = $results['gemini'];
    $deepseek = $results['deepseek'];

    return $gemini->isSuccess()
        && $deepseek->isSuccess()
        && $gemini->category === 'judol'
        && $deepseek->category === 'judol'
        && $gemini->provider === 'gemini'
        && $deepseek->provider === 'deepseek';
}, $passed, $failed);

// 6. Integrasi Hasil Paralel ke AnalyzeLink
runTest("Orkestrator menggabungkan hasil Dual AI paralel dan Suricata", function () {
    $geminiSvc = new GeminiService();
    $deepSeekSvc = new DeepSeekService();
    $orch = new AiOrchestratorService($geminiSvc, $deepSeekSvc);

    $result = $orch->analyzeLink('https://slot-gacor777.top');

    return $result->source === 'parallel_dual_ai'
        && $result->alertClass === 'red'
        && count($result->findings) >= 2;
}, $passed, $failed);

echo "\nHASIL PENGUJIAN TAHAP 2: {$passed} PASS, {$failed} FAIL\n";
exit($failed > 0 ? 1 : 0);


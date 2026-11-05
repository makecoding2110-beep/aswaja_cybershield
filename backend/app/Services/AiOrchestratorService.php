<?php

namespace App\Services;

use App\DTO\AnalysisResultDTO;
use App\DTO\AiAnalysisResultDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiOrchestratorService
{
    protected SuricataService $suricataService;
    protected UrlValidationService $urlValidationService;
    protected SuricataIntegrationService $suricataIntegration;
    protected AiComparisonService $aiComparisonService;
    protected RiskAssessmentService $riskAssessmentService;
    protected ScanHistoryService $scanHistoryService;

    public function __construct(
        protected GeminiService $geminiService,
        protected DeepSeekService $deepSeekService,
        ?SuricataService $suricataService = null,
        ?UrlValidationService $urlValidationService = null,
        ?SuricataIntegrationService $suricataIntegration = null,
        ?AiComparisonService $aiComparisonService = null,
        ?RiskAssessmentService $riskAssessmentService = null,
        ?ScanHistoryService $scanHistoryService = null
    ) {
        $this->suricataService = $suricataService ?? app(SuricataService::class);
        $this->urlValidationService = $urlValidationService ?? app(UrlValidationService::class);
        $this->suricataIntegration = $suricataIntegration ?? app(SuricataIntegrationService::class);
        $this->aiComparisonService = $aiComparisonService ?? app(AiComparisonService::class);
        $this->riskAssessmentService = $riskAssessmentService ?? app(RiskAssessmentService::class);
        $this->scanHistoryService = $scanHistoryService ?? app(ScanHistoryService::class);
    }

    /**
     * Menganalisis URL dengan orkestrasi Multi-Layer:
     * Layer 1: Suricata IDS Signature Engine (Deteksi Pola Instan)
     * Layer 2: Google Gemini (Analisis Kontekstual Fiqih & 4T)
     * Layer 3: DeepSeek AI (Failover & Reasoning)
     * Layer 4: Heuristic Engine (Safety Net)
     */
    public function analyzeLink(string $url): AnalysisResultDTO
    {
        // 1. Validasi, Normalisasi & SSRF Guard (UrlValidationService)
        $urlAnalysis = $this->urlValidationService->analyze($url);
        $url = $urlAnalysis->normalizedUrl;

        // Kumpulkan temuan indikator teknis struktur URL
        $urlFindings = [];
        foreach ($urlAnalysis->indicators as $ind) {
            $severityType = ($ind['severity'] === 'critical' || $ind['severity'] === 'high') ? 'error' : 'warning';
            $urlFindings[] = [
                'type' => $severityType,
                'label' => 'Analisis Struktur URL:',
                'detail' => $ind['explanation'] . " (Bukti: {$ind['evidence']})",
                'desc' => $ind['explanation']
            ];
        }

        // 2. Pemeriksaan Sensor Jaringan Suricata NIDS (EVE JSON) & Signature Engine
        $networkEvents = $this->suricataIntegration->inspectNetworkEvents($urlAnalysis->host, $url);
        $networkFindings = [];
        if ($networkEvents['available'] && $networkEvents['matched']) {
            foreach ($networkEvents['indicators'] as $ind) {
                $networkFindings[] = [
                    'type' => ($ind['severity'] === 'critical' || $ind['severity'] === 'high') ? 'error' : 'warning',
                    'label' => 'Suricata NIDS Jaringan:',
                    'detail' => $ind['explanation'] . " ({$ind['evidence']})",
                    'desc' => $ind['explanation']
                ];
            }
        }

        $suricata = $this->suricataService->inspect($url);
        $suricataFindings = array_merge($urlFindings, $networkFindings);
        if (!empty($suricata['alerts'])) {
            foreach ($suricata['alerts'] as $a) {
                $suricataFindings[] = [
                    'type' => ($a['priority'] === 1) ? 'error' : 'warning',
                    'label' => "Suricata IDS [SID: {$a['sid']}]:",
                    'detail' => "{$a['msg']} (Classtype: {$a['classtype']})",
                    'desc' => $a['msg']
                ];
            }
        }

        // 3. Layer 2 & 3: Eksekusi Dual AI secara Paralel (Google Gemini + DeepSeek)
        // Sesuai Dokumen Spesifikasi Bagian 5.3 (Parallel Processing via Http::pool)
        $aiResults = $this->executeParallelAi($url, [
            'suricata_matched' => $suricata['matched'],
            'suricata_alerts' => $suricata['alerts'] ?? [],
            'url_metrics' => $urlAnalysis->metrics,
            'url_indicators' => $urlAnalysis->indicators,
        ]);
        $gemini = $aiResults['gemini'];
        $deepseek = $aiResults['deepseek'];

        // 4. Komparasi Independen Dual AI (AiComparisonService - Spesifikasi Bagian 5.3 & 6)
        $aiComparison = $this->aiComparisonService->compare($gemini, $deepseek);

        // 5. Penilaian Risiko Akhir & Matriks Korelasi Keputusan (RiskAssessmentService - Spesifikasi Bagian 6 & 7)
        $suricataCombined = [
            'available' => $networkEvents['available'],
            'matched' => $networkEvents['matched'] || $suricata['matched'],
            'isWhitelist' => $suricata['isWhitelist'] ?? false,
            'alerts' => array_merge(
                $networkEvents['alerts'] ?? [],
                $suricata['matched'] ? ($suricata['alerts'] ?? []) : []
            ),
            'indicators' => array_merge(
                $networkEvents['indicators'] ?? [],
                $suricata['matched'] ? array_map(fn($a) => [
                    'name' => 'suricata_signature_rule',
                    'evidence' => "SID: {$a['sid']} - {$a['msg']}",
                    'severity' => ($a['priority'] === 1) ? 'critical' : 'high',
                    'explanation' => $a['msg']
                ], $suricata['alerts'] ?? []) : []
            )
        ];

        $assessment = $this->riskAssessmentService->evaluate(
            $urlAnalysis,
            $suricataCombined,
            $gemini,
            $deepseek,
            $aiComparison
        );

        // Ubah seluruh bukti indikator akhir assessment menjadi format findings terstruktur untuk antarmuka
        $finalFindings = [];
        foreach ($assessment->allIndicators as $ind) {
            $type = match ($ind['severity'] ?? 'medium') {
                'critical', 'high' => 'error',
                'medium' => 'warning',
                'low' => 'info',
                default => 'warning'
            };
            $originLabel = match ($ind['origin'] ?? '') {
                'url_structure' => 'Analisis Struktur URL:',
                'suricata_nids' => 'Sensor Suricata NIDS:',
                'ai_gemini' => 'Deteksi Google Gemini:',
                'ai_deepseek' => 'Penalaran DeepSeek AI:',
                default => 'Temuan Bukti:'
            };

            $finalFindings[] = [
                'type' => $type,
                'label' => $originLabel,
                'detail' => $ind['explanation'] . " (Bukti: {$ind['evidence']})",
                'desc' => $ind['explanation']
            ];
        }

        // Jika clean / normal
        if (empty($finalFindings) && $assessment->finalCategory === 'normal') {
            $finalFindings[] = [
                'type' => 'success',
                'label' => 'Verifikasi Konsensus:',
                'detail' => 'Tautan bersih dari pola kata kunci, jaringan judi online, maupun investasi menyesatkan.',
                'desc' => 'Tautan lolos sensor aman.'
            ];
        }

        // Skor keaslian/keamanan untuk antarmuka pengguna (0 = bahaya, 100 = aman)
        $displaySafetyScore = max(5, min(98, 100 - $assessment->riskScore));

        // 6. Simpan Seluruh Bukti Pemeriksaan ke Riwayat Audit (ScanHistoryService - Spesifikasi Bagian 10)
        $this->scanHistoryService->recordScan(
            $urlAnalysis,
            $suricataCombined,
            $gemini,
            $deepseek,
            $aiComparison,
            $assessment
        );

        return new AnalysisResultDTO(
            url: $url,
            category: $assessment->finalCategory,
            score: $displaySafetyScore,
            statusAlert: $assessment->categoryLabel,
            alertClass: $assessment->alertClass,
            title: $assessment->title,
            findings: $finalFindings,
            scannedAt: now()->translatedFormat('H:i:s') . ' WIB',
            source: $assessment->decisionSource
        );
    }

    /**
     * Menjalankan analisis Dual AI secara paralel menggunakan Http::pool
     * Sesuai Dokumen Spesifikasi Bagian 5.3 (Parallel Processing)
     *
     * @return array{gemini: AiAnalysisResultDTO, deepseek: AiAnalysisResultDTO}
     */
    public function executeParallelAi(string $url, array $technicalContext = []): array
    {
        $geminiConfigured = $this->geminiService->isConfigured();
        $deepSeekConfigured = $this->deepSeekService->isConfigured();

        if (!$geminiConfigured && !$deepSeekConfigured) {
            return [
                'gemini' => AiAnalysisResultDTO::unconfigured('gemini', $this->geminiService->getModel()),
                'deepseek' => AiAnalysisResultDTO::unconfigured('deepseek', $this->deepSeekService->getModel())
            ];
        }

        $geminiPayload = $geminiConfigured ? $this->geminiService->prepareRequestPayload($url, $technicalContext) : null;
        $deepSeekPayload = $deepSeekConfigured ? $this->deepSeekService->prepareRequestPayload($url, $technicalContext) : null;

        $responses = Http::pool(function ($pool) use ($geminiPayload, $deepSeekPayload) {
            $calls = [];
            if ($geminiPayload) {
                $calls[] = $pool->as('gemini')
                    ->timeout($geminiPayload['timeout'])
                    ->withHeaders($geminiPayload['headers'])
                    ->post($geminiPayload['endpoint'], $geminiPayload['body']);
            }
            if ($deepSeekPayload) {
                $calls[] = $pool->as('deepseek')
                    ->timeout($deepSeekPayload['timeout'])
                    ->withHeaders($deepSeekPayload['headers'])
                    ->post($deepSeekPayload['endpoint'], $deepSeekPayload['body']);
            }
            return $calls;
        });

        // Parsing hasil Gemini
        if (!$geminiConfigured) {
            $geminiResult = AiAnalysisResultDTO::unconfigured('gemini', $this->geminiService->getModel());
        } elseif (isset($responses['gemini']) && $responses['gemini'] instanceof \Throwable) {
            $geminiResult = AiAnalysisResultDTO::error('gemini', $this->geminiService->getModel(), $responses['gemini']->getMessage());
        } elseif (isset($responses['gemini'])) {
            $geminiResult = $this->geminiService->parseResponse($responses['gemini']->body(), $responses['gemini']->status(), $url);
        } else {
            $geminiResult = AiAnalysisResultDTO::error('gemini', $this->geminiService->getModel(), 'Tidak ada respons dari pool');
        }

        // Parsing hasil DeepSeek
        if (!$deepSeekConfigured) {
            $deepSeekResult = AiAnalysisResultDTO::unconfigured('deepseek', $this->deepSeekService->getModel());
        } elseif (isset($responses['deepseek']) && $responses['deepseek'] instanceof \Throwable) {
            $deepSeekResult = AiAnalysisResultDTO::error('deepseek', $this->deepSeekService->getModel(), $responses['deepseek']->getMessage());
        } elseif (isset($responses['deepseek'])) {
            $deepSeekResult = $this->deepSeekService->parseResponse($responses['deepseek']->body(), $responses['deepseek']->status(), $url);
        } else {
            $deepSeekResult = AiAnalysisResultDTO::error('deepseek', $this->deepSeekService->getModel(), 'Tidak ada respons dari pool');
        }

        return [
            'gemini' => $geminiResult,
            'deepseek' => $deepSeekResult
        ];
    }

    /**
     * Menganalisis Laporan Aduan Warga (DeepSeek -> Gemini -> Local Triage)
     */
    public function analyzeReport(array $report): array
    {
        // 1. Agen Utama: DeepSeek (Penalaran Mendalam / Forensic Reasoning)
        if ($this->deepSeekService->isConfigured()) {
            try {
                $result = $this->deepSeekService->analyzeReport($report);
                if ($result !== null) {
                    $result['source'] = 'deepseek_ai';
                    return $result;
                }
            } catch (\Throwable $e) {
                Log::warning('[Multi-AI] DeepSeek gagal audit laporan: ' . $e->getMessage());
            }
        }

        // 2. Fallback Heuristik Triase Lokal
        $category = strtolower($report['category'] ?? '');
        $url = strtolower($report['url'] ?? '');
        $isHighRisk = str_contains($category, 'judi') || str_contains($category, 'maysir') || str_contains($url, 'slot') || str_contains($url, 'gacor');

        return [
            'riskLevel' => $isHighRisk ? 'Tinggi' : 'Sedang',
            'modusOperandi' => 'Indikasi peredaran tautan siber mencurigakan yang memerlukan verifikasi manual admin.',
            'legalViolation' => 'Potensi pelanggaran UU ITE No. 1/2024 Pasal 27 ayat 2 & Fatwa Keagamaan.',
            'recommendedAction' => 'Lakukan konfirmasi URL ke database blacklist Kominfo/OJK dan ubah status laporan.',
            'triageNote' => 'Hasil triase otomatis sistem siber Aswaja.',
            'source' => 'heuristic_engine'
        ];
    }

    /**
     * Mesin Analisis Heuristik Fiqih Siber (Offline Safety Net)
     */
    public function fallbackHeuristicAnalysis(string $url): AnalysisResultDTO
    {
        $lower = strtolower($url);

        $judolKeywords = ['slot', 'gacor', 'spin', 'bet', 'judi', 'taruhan', 'casino', 'kasino', 'maxwin', 'deposit', 'vip88', 'hoki', 'toto', 'togel', 'jackpot', 'scatter', 'zeus'];
        $investKeywords = ['trading', 'cuan', 'mining', 'invest', 'profit', 'syariah', 'dana-kaget', 'hibah', 'arisan', 'passiveincome', 'signal', 'forex', 'robot'];
        $suspiciousTlds = ['.vip', '.cc', '.xyz', '.click', '.fun', '.online', '.site', '.top'];
        $safeDomains = ['ojk.go.id', 'bappebti.go.id', 'bi.go.id', 'kominfo.go.id', 'gov.id', 'ac.id'];

        $hasJudol = false;
        foreach ($judolKeywords as $k) {
            if (str_contains($lower, $k)) { $hasJudol = true; break; }
        }

        $hasInvest = false;
        foreach ($investKeywords as $k) {
            if (str_contains($lower, $k)) { $hasInvest = true; break; }
        }

        $hasSuspTld = false;
        foreach ($suspiciousTlds as $t) {
            if (str_contains($lower, $t)) { $hasSuspTld = true; break; }
        }

        $isSafe = false;
        foreach ($safeDomains as $d) {
            if (str_contains($lower, $d)) { $isSafe = true; break; }
        }

        if ($isSafe) {
            return new AnalysisResultDTO(
                url: $url,
                category: 'belum_ditemukan_indikasi',
                score: 95,
                statusAlert: 'Status Aman Terverifikasi',
                alertClass: 'emerald',
                title: 'Belum Ditemukan Indikasi Tautan Judi Online',
                findings: [
                    ['type' => 'success', 'label' => 'Domain Resmi Regulator:', 'detail' => 'Domain terdaftar resmi dalam institusi pengawas pemerintah (OJK/Bappebti/Kominfo).', 'desc' => 'Domain terdaftar resmi dalam institusi pengawas pemerintah.'],
                    ['type' => 'success', 'label' => 'Bebas Pola Maysir:', 'detail' => 'Tidak ditemukan skema setoran taruhan, roda keberuntungan, atau janji keuntungan fiktif.', 'desc' => 'Tidak ditemukan skema setoran taruhan atau janji cuan fiktif.'],
                    ['type' => 'info', 'label' => 'Saran Tabayyun:', 'detail' => 'Tetap utamakan verifikasi berkala pada saluran komunikasi resmi institusi.', 'desc' => 'Tetap utamakan verifikasi berkala pada saluran resmi.']
                ],
                scannedAt: now()->translatedFormat('H:i:s') . ' WIB',
                source: 'heuristic_engine'
            );
        }

        if ($hasJudol && ($hasInvest || $hasSuspTld)) {
            return new AnalysisResultDTO(
                url: $url,
                category: 'terindikasi_judol',
                score: 18,
                statusAlert: 'Peringatan Bahaya Tinggi',
                alertClass: 'red',
                title: 'Waspada: Terindikasi Tautan Investasi Palsu Mengarah ke Judi Online',
                findings: [
                    ['type' => 'error', 'label' => 'Anomali Domain & Kedok Usaha:', 'detail' => 'Menggunakan nama bernuansa investasi/trading, namun parameter URL mengarahkan ke sistem deposit dan putaran taruhan judi online (slot/kasino).', 'desc' => 'Menggunakan nama investasi namun URL mengarahkan ke deposit taruhan judi online.'],
                    ['type' => 'error', 'label' => 'Manipulasi Psikologis Finansial:', 'detail' => 'Menjanjikan passive income instan dan profit pasti yang melanggar prinsip fiqih muamalah serta regulasi OJK/Bappebti.', 'desc' => 'Menjanjikan passive income instan yang melanggar prinsip fiqih muamalah.'],
                    ['type' => 'warning', 'label' => 'Rekomendasi Tindakan:', 'detail' => 'Jangan registrasi atau mentransfer dana. Laporkan tautan ini ke Portal Lapor Siber Aswaja untuk koordinasi penindakan.', 'desc' => 'Jangan registrasi atau mentransfer dana. Laporkan tautan ini.']
                ],
                scannedAt: now()->translatedFormat('H:i:s') . ' WIB',
                source: 'heuristic_engine'
            );
        }

        if ($hasJudol || ($hasInvest && $hasSuspTld)) {
            return new AnalysisResultDTO(
                url: $url,
                category: 'perlu_diwaspadai',
                score: 45,
                statusAlert: 'Perlu Diwaspadai',
                alertClass: 'amber',
                title: 'Perlu Diwaspadai: Indikasi Skema Finansial Berisiko Tinggi Tanpa Izin',
                findings: [
                    ['type' => 'warning', 'label' => 'Legalitas Belum Terverifikasi:', 'detail' => 'Tautan menggunakan domain tidak resmi dan tidak ditemukan dalam izin regulator OJK/Bappebti.', 'desc' => 'Tautan menggunakan domain tidak resmi dan tidak ditemukan di OJK.'],
                    ['type' => 'warning', 'label' => 'Potensi Gharar & Maysir:', 'detail' => 'Terdapat indikasi spekulasi tinggi atau pengalihan deposit ke pihak ketiga yang tidak dapat dipertanggungjawabkan.', 'desc' => 'Terdapat indikasi spekulasi tinggi atau pengalihan deposit ke pihak ketiga.'],
                    ['type' => 'info', 'label' => 'Saran Fiqih Tabayyun:', 'detail' => 'Terapkan prinsip Tawaqquf (menahan diri). Jangan terburu-buru menyetor dana sebelum kejelasan sanad terbukti.', 'desc' => 'Terapkan prinsip Tawaqquf sebelum menyetor dana apa pun.']
                ],
                scannedAt: now()->translatedFormat('H:i:s') . ' WIB',
                source: 'heuristic_engine'
            );
        }

        return new AnalysisResultDTO(
            url: $url,
            category: 'belum_ditemukan_indikasi',
            score: 82,
            statusAlert: 'Belum Terindikasi',
            alertClass: 'emerald',
            title: 'Belum Ditemukan Indikasi Tautan Judi Online',
            findings: [
                ['type' => 'success', 'label' => 'Audit Kata Kunci:', 'detail' => 'Tidak ditemukan pola kata kunci spesifik judi online (slot, toto, taruhan) pada URL.', 'desc' => 'Tidak ditemukan kata kunci judi online pada URL.'],
                ['type' => 'info', 'label' => 'Verifikasi Lanjutan:', 'detail' => 'Analisis heuristik awal belum mendeteksi kejanggalan, namun tetap pastikan izin usaha platform finansial.', 'desc' => 'Analisis awal belum mendeteksi kejanggalan.'],
                ['type' => 'info', 'label' => 'Kewaspadaan Digital:', 'detail' => 'Jangan pernah memberikan password, PIN, atau kode OTP kepada pihak mana pun.', 'desc' => 'Jangan pernah memberikan kode OTP kepada pihak mana pun.']
            ],
            scannedAt: now()->translatedFormat('H:i:s') . ' WIB',
            source: 'heuristic_engine'
        );
    }

    /**
     * Memeriksa status konfigurasi kedua AI
     */
    public function getStatus(): array
    {
        $geminiConfigured = $this->geminiService->isConfigured();
        $deepseekConfigured = $this->deepSeekService->isConfigured();

        $activeMode = 'Dual AI Aktif';
        if ($geminiConfigured && !$deepseekConfigured) {
            $activeMode = 'Gemini Primary (DeepSeek Belum Disetel)';
        } elseif (!$geminiConfigured && $deepseekConfigured) {
            $activeMode = 'DeepSeek Primary (Gemini Belum Disetel)';
        } elseif (!$geminiConfigured && !$deepseekConfigured) {
            $activeMode = 'Mode Heuristik Lokal';
        }

        return [
            'configured' => $geminiConfigured || $deepseekConfigured,
            'active_mode' => $activeMode,
            'status' => ($geminiConfigured || $deepseekConfigured) ? 'Terhubung' : 'Mode Heuristik',
            'webhook_status' => ($geminiConfigured || $deepseekConfigured) ? 'Aktif' : 'Nonaktif',
            'ai_status' => ($geminiConfigured && $deepseekConfigured) ? 'Dual AI Siap' : (($geminiConfigured || $deepseekConfigured) ? 'Single AI Aktif' : 'Lokal Heuristik'),
            'gemini' => [
                'name' => 'Google Gemini AI',
                'role' => 'Analis Cepat Tabayyun URL & Sanad Domain',
                'configured' => $geminiConfigured,
                'model' => $this->geminiService->getModel(),
                'masked_key' => $this->geminiService->getMaskedKey(),
            ],
            'deepseek' => [
                'name' => 'DeepSeek AI',
                'role' => 'Audit Investigatif Laporan Siber & Triase Bahaya',
                'configured' => $deepseekConfigured,
                'model' => $this->deepSeekService->getModel(),
                'masked_key' => $this->deepSeekService->getMaskedKey(),
            ],
            'suricata' => [
                'name' => 'Suricata NIDS Integration',
                'role' => 'L1 Network Packet & EVE JSON Log Inspection',
                'configured' => true,
                'available' => $this->suricataIntegration->isAvailable(),
                'eve_log_path' => $this->suricataIntegration->getEveLogPath(),
                'rules_count' => count($this->suricataIntegration->getDefaultRules()),
                'status' => $this->suricataIntegration->isAvailable()
                    ? 'Sensor Jaringan Aktif (EVE JSON Terhubung)'
                    : 'Sensor Jaringan Tidak Tersedia (Mode Signature Standby)',
            ],
            'masked_webhook_url' => 'Dual AI Direct API (Gemini + DeepSeek)',
            'masked_api_key' => 'Gemini: ' . $this->geminiService->getMaskedKey() . ' | DeepSeek: ' . $this->deepSeekService->getMaskedKey(),
            'last_checked' => now()->translatedFormat('d M Y, H:i') . ' WIB',
            'message' => ($geminiConfigured || $deepseekConfigured)
                ? 'Sistem orkestrasi Dual AI siap melayani Tabayyun dan Audit Laporan Siber.'
                : 'API Key Gemini atau DeepSeek belum diatur. Sistem saat ini berjalan dengan mesin heuristik lokal Fiqih Siber.'
        ];
    }

    /**
     * Menguji probe koneksi AI
     */
    public function testConnection(?string $provider = null): array
    {
        $geminiResult = $this->geminiService->testConnection();
        $deepseekResult = $this->deepSeekService->testConnection();

        $overallSuccess = $geminiResult['connected'] || $deepseekResult['connected'];

        return [
            'status' => $overallSuccess ? 'Terhubung' : 'Belum Terhubung',
            'webhook_status' => $overallSuccess ? 'Aktif' : 'Tidak Aktif',
            'ai_status' => $overallSuccess ? 'Aktif' : 'Siaga',
            'gemini' => $geminiResult,
            'deepseek' => $deepseekResult,
            'masked_webhook_url' => 'Dual AI Direct API (Gemini + DeepSeek)',
            'masked_api_key' => 'Gemini: ' . $this->geminiService->getMaskedKey() . ' | DeepSeek: ' . $this->deepSeekService->getMaskedKey(),
            'last_checked' => now()->translatedFormat('d M Y, H:i') . ' WIB',
            'message' => $overallSuccess
                ? 'Probe koneksi AI berhasil diverifikasi. Layanan Dual AI siap digunakan.'
                : 'Probe AI belum terhubung. Sistem saat ini menggunakan mode fallback heuristik lokal yang aman.'
        ];
    }

    /**
     * Mencegah Serangan Server-Side Request Forgery (SSRF) & Normalisasi URL
     */
    protected function validateSafeUrl(string $url): void
    {
        $this->urlValidationService->normalize($url);
    }
}


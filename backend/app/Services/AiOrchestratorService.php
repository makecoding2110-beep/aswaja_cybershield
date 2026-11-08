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
    protected UrlThreatAnalyzer $threatAnalyzer;
    protected SuricataIntegrationService $suricataIntegration;
    protected AiComparisonService $aiComparisonService;
    protected RiskAssessmentService $riskAssessmentService;
    protected ScanHistoryService $scanHistoryService;

    public function __construct(
        protected GeminiService $geminiService,
        ?SuricataService $suricataService = null,
        ?UrlValidationService $urlValidationService = null,
        ?UrlThreatAnalyzer $threatAnalyzer = null,
        ?SuricataIntegrationService $suricataIntegration = null,
        ?AiComparisonService $aiComparisonService = null,
        ?RiskAssessmentService $riskAssessmentService = null,
        ?ScanHistoryService $scanHistoryService = null
    ) {
        $this->suricataService = $suricataService ?? app(SuricataService::class);
        $this->urlValidationService = $urlValidationService ?? app(UrlValidationService::class);
        $this->threatAnalyzer = $threatAnalyzer ?? app(UrlThreatAnalyzer::class);
        $this->suricataIntegration = $suricataIntegration ?? app(SuricataIntegrationService::class);
        $this->aiComparisonService = $aiComparisonService ?? app(AiComparisonService::class);
        $this->riskAssessmentService = $riskAssessmentService ?? app(RiskAssessmentService::class);
        $this->scanHistoryService = $scanHistoryService ?? app(ScanHistoryService::class);
    }

    /**
     * Menganalisis URL dengan orkestrasi Multi-Layer:
     * Layer 1: Suricata IDS Signature Engine (Deteksi Pola Instan)
     * Layer 2: Google Gemini AI (Analisis Kontekstual Fiqih & 4T)
     * Layer 3: Heuristic Engine (Safety Net)
     */
    public function analyzeLink(string $url): AnalysisResultDTO
    {
        // 1. Validasi, Normalisasi & SSRF Guard (UrlValidationService)
        $urlAnalysis = $this->urlValidationService->analyze($url);
        $url = $urlAnalysis->normalizedUrl;

        // 1.A Cek Konsistensi Riwayat Scan (Forensic Cache): Tautan yang sama selalu menghasilkan vonis identik dan tidak menambah row baru
        $cachedRecord = null;
        $cleanNorm = rtrim($url, '/');
        $noProto = preg_replace('#^https?://(www\.)?#i', '', $cleanNorm);

        try {
            $cachedRecord = \App\Models\LinkScan::where('normalized_url', $url)
                ->orWhere('normalized_url', $cleanNorm)
                ->orWhere('normalized_url', $cleanNorm . '/')
                ->orWhere('original_url', $urlAnalysis->originalUrl)
                ->orWhere('original_url', rtrim($urlAnalysis->originalUrl, '/'))
                ->orWhere('normalized_url', 'http://' . $noProto)
                ->orWhere('normalized_url', 'https://' . $noProto)
                ->orWhere('normalized_url', 'http://' . $noProto . '/')
                ->orWhere('normalized_url', 'https://' . $noProto . '/')
                ->orWhere('normalized_url', 'http://www.' . $noProto)
                ->orWhere('normalized_url', 'https://www.' . $noProto)
                ->orWhere('normalized_url', 'http://www.' . $noProto . '/')
                ->orWhere('normalized_url', 'https://www.' . $noProto . '/')
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (\Throwable $e) {
            // DB offline / exception
        }

        if (!$cachedRecord) {
            try {
                $mockPath = storage_path('app/mock_database.json');
                if (file_exists($mockPath)) {
                    $mock = json_decode(file_get_contents($mockPath), true) ?? [];
                    foreach ($mock['link_scans'] ?? [] as $s) {
                        $sNorm = rtrim($s['normalized_url'] ?? '', '/');
                        $sNoProto = preg_replace('#^https?://(www\.)?#i', '', $sNorm);
                        if ($sNorm === $cleanNorm || $sNoProto === $noProto || rtrim($s['original_url'] ?? '', '/') === rtrim($urlAnalysis->originalUrl, '/')) {
                            $cachedRecord = (object) $s;
                            break;
                        }
                    }
                }
            } catch (\Throwable $m) {}
        }

        if ($cachedRecord) {
            $displaySafety = max(5, min(98, 100 - (int)$cachedRecord->risk_score));
            $storedIndicators = is_string($cachedRecord->indicators)
                ? (json_decode($cachedRecord->indicators, true) ?? [])
                : ($cachedRecord->indicators ?? []);
            $storedTechniques = is_string($cachedRecord->techniques)
                ? (json_decode($cachedRecord->techniques, true) ?? [])
                : ($cachedRecord->techniques ?? []);

            $cachedFindings = [];
            $seenKeys = [];
            foreach ($storedIndicators as $ind) {
                $rawEvidence = (string)($ind['evidence'] ?? '');
                $rawExplanation = (string)($ind['explanation'] ?? '');
                $normKey = strtolower(trim($rawEvidence !== '' ? $rawEvidence : $rawExplanation));
                if ($normKey === '' || isset($seenKeys[$normKey])) continue;
                $seenKeys[$normKey] = true;

                $type = match ($ind['severity'] ?? 'medium') {
                    'critical', 'high' => 'error',
                    'medium' => 'warning',
                    'low' => 'info',
                    default => 'warning'
                };

                $cachedFindings[] = [
                    'type' => $type,
                    'label' => 'Bukti Deteksi Siber:',
                    'detail' => $rawExplanation . ($rawEvidence !== '' ? " ({$rawEvidence})" : ''),
                    'desc' => $rawExplanation
                ];
            }

            if (empty($cachedFindings)) {
                $cachedFindings[] = [
                    'type' => ($cachedRecord->risk_score >= 85 ? 'error' : ($cachedRecord->risk_score >= 51 ? 'warning' : 'info')),
                    'label' => 'Status Audit Terverifikasi:',
                    'detail' => $cachedRecord->classification_reason,
                    'desc' => $cachedRecord->classification_reason
                ];
            }

            $alertClass = match (true) {
                $cachedRecord->risk_score >= 85 => 'red',
                $cachedRecord->risk_score >= 51 => 'orange',
                $cachedRecord->risk_score >= 26 => 'amber',
                default => 'emerald'
            };

            $title = match ($cachedRecord->final_category) {
                'terindikasi_judol' => 'Waspada: Terindikasi Judi Online',
                'terindikasi_investasi' => 'Peringatan: Terindikasi Investasi Bodong / Ilegal',
                'mencurigakan' => 'Perhatian: Terdeteksi Pola Tautan Mencurigakan',
                'normal' => 'Bersih: Tidak Ditemukan Pola Signature Berbahaya',
                default => 'Hasil Pemeriksaan Forensik Tautan'
            };

            $statusAlert = match ($cachedRecord->final_category) {
                'terindikasi_judol' => 'Berat (Sangat Berbahaya)',
                'terindikasi_investasi' => 'Peringatan Bahaya Tinggi',
                'mencurigakan' => 'Tinggi (Perlu Pemeriksaan)',
                'normal' => 'Aman (Risiko Rendah)',
                default => 'Hasil Analisis'
            };

            return new AnalysisResultDTO(
                url: $url,
                category: $cachedRecord->final_category,
                score: $displaySafety,
                statusAlert: $statusAlert,
                alertClass: $alertClass,
                title: $title,
                findings: $cachedFindings,
                scannedAt: now()->timezone('Asia/Makassar')->translatedFormat('H:i:s') . ' WITA',
                source: $cachedRecord->decision_source ?? 'threat_analyzer',
                riskScore: (int) $cachedRecord->risk_score,
                safetyScore: $displaySafety,
                finalCategory: $cachedRecord->final_category,
                techniques: $storedTechniques
            );
        }

        // 1.B Analisis 3 Teknik Penyusupan Tautan (UrlThreatAnalyzer: Defacement, Open Redirect, Typosquatting)
        $threatResult = $this->threatAnalyzer->analyze($url);

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

        // Tambahkan temuan indikator dari UrlThreatAnalyzer
        foreach ($threatResult['indicators'] as $ind) {
            $severityType = ($ind['severity'] === 'critical' || $ind['severity'] === 'high') ? 'error' : 'warning';
            $labelPrefix = match ($ind['technique'] ?? '') {
                'web_defacement' => 'Defacement / Subdomain:',
                'open_redirect' => 'Open Redirect Tautan:',
                'typosquatting' => 'Typosquatting Instansi:',
                'kamuflase_investasi' => 'Kamuflase Finansial:',
                default => 'Analisis Pola Tautan:'
            };
            $urlFindings[] = [
                'type' => $severityType,
                'label' => $labelPrefix,
                'detail' => $ind['explanation'] . " (Bukti: {$ind['evidence']})",
                'desc' => $ind['explanation']
            ];
        }

        // 2. Pemeriksaan Sensor Jaringan (EVE JSON) & Signature Engine
        $networkEvents = $this->suricataIntegration->inspectNetworkEvents($urlAnalysis->host, $url);
        $networkFindings = [];
        if ($networkEvents['available'] && $networkEvents['matched']) {
            foreach ($networkEvents['indicators'] as $ind) {
                $cleanEv = preg_replace('/SID:\s*\d+\s*-\s*/i', '', (string)$ind['evidence']);
                $networkFindings[] = [
                    'type' => ($ind['severity'] === 'critical' || $ind['severity'] === 'high') ? 'error' : 'warning',
                    'label' => 'Sensor Keamanan Jaringan:',
                    'detail' => $ind['explanation'] . ($cleanEv !== '' ? " ({$cleanEv})" : ''),
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
                    'label' => "Deteksi Signature Jaringan:",
                    'detail' => "{$a['msg']}",
                    'desc' => $a['msg']
                ];
            }
        }

        // 3. Layer 2: Eksekusi Analisis Google Gemini AI
        $technicalContext = [
            'threat_analysis' => $threatResult,
            'detected_techniques' => $threatResult['techniques'] ?? [],
            'suricata_matched' => $suricata['matched'],
            'suricata_alerts' => $suricata['alerts'] ?? [],
            'url_metrics' => $urlAnalysis->metrics,
            'url_indicators' => array_merge($urlAnalysis->indicators, $threatResult['indicators']),
        ];

        $gemini = $this->geminiService->analyzeUrl($url, $technicalContext);

        // 4. Evaluasi & Validasi Analisis AI
        $aiComparison = $this->aiComparisonService->compare($gemini, null);

        // 5. Penilaian Risiko Akhir & Matriks Korelasi Keputusan (RiskAssessmentService)
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
            null,
            $aiComparison,
            $threatResult
        );

        // Ubah seluruh bukti indikator akhir assessment menjadi format findings terstruktur untuk antarmuka publik
        $finalFindings = [];
        $seenDeduplicationKeys = [];

        foreach ($assessment->allIndicators as $ind) {
            $rawEvidence = (string)($ind['evidence'] ?? '');
            $rawExplanation = (string)($ind['explanation'] ?? '');

            // Kunci deduplikasi berbasis bukti/frasa yang ditemukan agar tidak berulang
            $normKey = strtolower(trim($rawEvidence !== '' ? $rawEvidence : $rawExplanation));
            if (isset($seenDeduplicationKeys[$normKey])) {
                continue;
            }
            $seenDeduplicationKeys[$normKey] = true;

            $type = match ($ind['severity'] ?? 'medium') {
                'critical', 'high' => 'error',
                'medium' => 'warning',
                'low' => 'info',
                default => 'warning'
            };

            // Label ramah pengguna (tanpa mengekspos nama engine internal seperti Google Gemini atau Suricata)
            $originLabel = match ($ind['origin'] ?? '') {
                'threat_analyzer' => 'Inspeksi Konten & Penyusupan:',
                'url_structure' => 'Analisis Struktur URL:',
                'suricata_nids' => 'Sensor Keamanan Jaringan:',
                'ai_gemini', 'ai_openai', 'ai_chatgpt' => 'Analisis Pola & Konten:',
                default => 'Temuan Bukti:'
            };

            // Bersihkan teks penjelasan dari embel-embel teknis/nama model
            $cleanExplanation = preg_replace('/\s*\[(?:Analisis|Penalaran)\s+[^\]]+\]/i', '', $rawExplanation);
            $cleanExplanation = str_ireplace(['google gemini', 'gemini', 'suricata nids', 'suricata', 'chatgpt', 'openai'], 'sistem cerdas', $cleanExplanation);

            // Bersihkan teks bukti (hilangkan ID signature teknis internal seperti "SID: 1000001 - ")
            $cleanEvidence = preg_replace('/SID:\s*\d+\s*-\s*/i', '', $rawEvidence);
            $cleanEvidence = str_ireplace(['suricata', 'gemini', 'openai'], '', $cleanEvidence);
            $cleanEvidence = trim($cleanEvidence);

            $finalFindings[] = [
                'type' => $type,
                'label' => $originLabel,
                'detail' => $cleanExplanation . ($cleanEvidence !== '' ? " (Bukti: {$cleanEvidence})" : ''),
                'desc' => $cleanExplanation
            ];
        }

        // Urutkan agar temuan tingkat kritis/tinggi ('error') selalu tampil paling atas
        usort($finalFindings, function (array $a, array $b): int {
            $order = ['error' => 1, 'warning' => 2, 'info' => 3, 'success' => 4];
            return ($order[$a['type']] ?? 9) <=> ($order[$b['type']] ?? 9);
        });

        // Jika clean / normal / belum ditemukan indikasi bahaya
        if (empty($finalFindings)) {
            if (in_array($assessment->finalCategory, ['normal', 'belum_ditemukan_indikasi', 'aman'], true) || $assessment->riskScore < 30) {
                $finalFindings = [
                    [
                        'type' => 'success',
                        'label' => 'Audit Sanad Domain:',
                        'detail' => 'Domain beroperasi secara legal, memiliki reputasi bersih, dan tidak terdaftar dalam basis data pemblokiran judol atau kloning finansial.',
                        'desc' => 'Domain beroperasi secara legal dan memiliki reputasi bersih.'
                    ],
                    [
                        'type' => 'success',
                        'label' => 'Analisis Muatan & Pola:',
                        'detail' => 'Tidak ditemukan skema setoran taruhan, roda putar keberuntungan acak, maupun klaim keuntungan instan yang mencurigakan.',
                        'desc' => 'Tidak ditemukan indikasi perjudian atau investasi bodong.'
                    ],
                    [
                        'type' => 'info',
                        'label' => 'Rekomendasi Tabayyun:',
                        'detail' => 'Tautan aman untuk diakses. Tetap terapkan prinsip kehati-hatian digital dan jaga kerahasiaan data pribadi Anda.',
                        'desc' => 'Tautan aman untuk diakses.'
                    ]
                ];
            } else {
                $finalFindings[] = [
                    'type' => 'info',
                    'label' => 'Verifikasi Konsensus:',
                    'detail' => 'Tidak ditemukan indikator signature bahaya aktif pada pemindaian waktu-nyata.',
                    'desc' => 'Tidak ditemukan indikator bahaya aktif.'
                ];
            }
        }

        // Skor keaslian/keamanan untuk antarmuka pengguna (0 = bahaya, 100 = aman)
        $displaySafetyScore = max(5, min(98, 100 - $assessment->riskScore));

        // 6. Simpan Seluruh Bukti Pemeriksaan ke Riwayat Audit (ScanHistoryService - Spesifikasi Bagian 10)
        $this->scanHistoryService->recordScan(
            $urlAnalysis,
            $suricataCombined,
            $gemini,
            null,
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
            scannedAt: now()->timezone('Asia/Makassar')->translatedFormat('H:i:s') . ' WITA',
            source: $assessment->decisionSource,
            riskScore: $assessment->riskScore,
            safetyScore: $displaySafetyScore,
            finalCategory: $assessment->finalCategory,
            techniques: $assessment->techniques ?? []
        );
    }

    /**
     * Menjalankan analisis AI menggunakan Google Gemini AI
     *
     * @return array{gemini: AiAnalysisResultDTO}
     */
    public function executeParallelAi(string $url, array $technicalContext = []): array
    {
        $geminiResult = $this->geminiService->analyzeUrl($url, $technicalContext);

        return [
            'gemini' => $geminiResult,
        ];
    }

    /**
     * Menganalisis Laporan Aduan Warga (Triase Otomatis Fiqih Siber)
     */
    public function analyzeReport(array $report): array
    {
        // Fallback Heuristik Triase Lokal
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
                scannedAt: now()->timezone('Asia/Makassar')->translatedFormat('H:i:s') . ' WITA',
                source: 'heuristic_engine',
                riskScore: 5,
                safetyScore: 95,
                finalCategory: 'normal'
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
                scannedAt: now()->timezone('Asia/Makassar')->translatedFormat('H:i:s') . ' WITA',
                source: 'heuristic_engine',
                riskScore: 82,
                safetyScore: 18,
                finalCategory: 'terindikasi_judol'
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
                scannedAt: now()->timezone('Asia/Makassar')->translatedFormat('H:i:s') . ' WITA',
                source: 'heuristic_engine',
                riskScore: 65,
                safetyScore: 35,
                finalCategory: 'mencurigakan'
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
            scannedAt: now()->timezone('Asia/Makassar')->translatedFormat('H:i:s') . ' WITA',
            source: 'heuristic_engine',
            riskScore: 18,
            safetyScore: 82,
            finalCategory: 'normal'
        );
    }

    /**
     * Memeriksa status konfigurasi AI (Google Gemini AI)
     */
    public function getStatus(): array
    {
        $geminiConfigured = $this->geminiService->isConfigured();

        return [
            'configured' => $geminiConfigured,
            'active_mode' => $geminiConfigured ? 'Google Gemini AI Aktif' : 'Mode Heuristik Lokal Fiqih Siber',
            'status' => $geminiConfigured ? 'Terhubung' : 'Mode Heuristik',
            'webhook_status' => $geminiConfigured ? 'Aktif' : 'Nonaktif',
            'ai_status' => $geminiConfigured ? 'Google Gemini AI Siap' : 'Lokal Heuristik',
            'gemini' => [
                'name' => 'Google Gemini AI',
                'role' => 'Analis Utama Tabayyun URL & Sanad Domain',
                'configured' => $geminiConfigured,
                'model' => $this->geminiService->getModel(),
                'masked_key' => $this->geminiService->getMaskedKey(),
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
                    : 'Sensor Jaringan Standby (Mode Signature Lokal)',
            ],
            'masked_webhook_url' => 'Google Gemini AI Engine',
            'masked_api_key' => 'Gemini: ' . $this->geminiService->getMaskedKey(),
            'last_checked' => now()->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') . ' WITA',
            'message' => $geminiConfigured
                ? 'Sistem analitik cerdas Google Gemini AI siap melayani Tabayyun dan Audit Tautan.'
                : 'API Key Google Gemini belum diatur. Sistem berjalan dengan mesin heuristik lokal Fiqih Siber.'
        ];
    }

    /**
     * Menguji probe koneksi AI
     */
    public function testConnection(?string $provider = null): array
    {
        $geminiResult = $this->geminiService->testConnection();
        $overallSuccess = $geminiResult['connected'];

        return [
            'status' => $overallSuccess ? 'Terhubung' : 'Belum Terhubung',
            'webhook_status' => $overallSuccess ? 'Aktif' : 'Tidak Aktif',
            'ai_status' => $overallSuccess ? 'Aktif' : 'Siaga',
            'gemini' => $geminiResult,
            'masked_webhook_url' => 'AI Engine (Google Gemini AI)',
            'masked_api_key' => 'Gemini: ' . $this->geminiService->getMaskedKey(),
            'last_checked' => now()->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') . ' WITA',
            'message' => $overallSuccess
                ? 'Probe koneksi AI berhasil diverifikasi. Layanan Google Gemini AI siap digunakan.'
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


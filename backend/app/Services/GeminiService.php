<?php

namespace App\Services;

use App\DTO\AiAnalysisResultDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service Integrasi Google Gemini API
 * Bertugas menganalisis struktur URL, kata kunci, angka, kalimat persuasi, dan indikasi penipuan.
 * Sesuai Dokumen Spesifikasi Bagian 5.1 & 5.4
 */
class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected int $timeout;

    public const ALLOWED_CATEGORIES = [
        'judol',
        'investasi_menyesatkan',
        'normal',
        'suspicious',
        'unknown'
    ];

    public const ALLOWED_RISK_LEVELS = [
        'low',
        'medium',
        'high',
        'critical'
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $this->model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash'));
        $this->timeout = (int) config('services.gemini.timeout', env('GEMINI_TIMEOUT', 15));
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getMaskedKey(): string
    {
        if (empty($this->apiKey)) {
            return 'Belum diatur';
        }
        $len = strlen($this->apiKey);
        if ($len <= 8) {
            return '***';
        }
        return substr($this->apiKey, 0, 6) . '...' . substr($this->apiKey, -4);
    }

    /**
     * Mempersiapkan payload HTTP request untuk eksekusi mandiri atau HTTP Pool paralel
     */
    public function prepareRequestPayload(string $url, array $technicalContext = []): array
    {
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($url, $technicalContext);

        $body = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n" . $userPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.1
            ]
        ];

        return [
            'endpoint' => $endpoint,
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'timeout' => $this->timeout
        ];
    }

    /**
     * Melakukan analisis URL secara langsung menggunakan Google Gemini API
     */
    public function analyzeUrl(string $url, array $technicalContext = []): AiAnalysisResultDTO
    {
        if (!$this->isConfigured()) {
            return AiAnalysisResultDTO::unconfigured('gemini', $this->model);
        }

        $prepared = $this->prepareRequestPayload($url, $technicalContext);

        try {
            $response = Http::timeout($prepared['timeout'])
                ->withHeaders($prepared['headers'])
                ->post($prepared['endpoint'], $prepared['body']);

            return $this->parseResponse($response->body(), $response->status(), $url);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('[Gemini AI] Timeout atau koneksi terputus: ' . $e->getMessage());
            return AiAnalysisResultDTO::error('gemini', $this->model, 'Koneksi ke Google Gemini API timeout: ' . $e->getMessage(), 'timeout');
        } catch (\Throwable $e) {
            Log::error('[Gemini AI] Gagal memanggil API: ' . $e->getMessage());
            return AiAnalysisResultDTO::error('gemini', $this->model, $e->getMessage(), 'error');
        }
    }

    /**
     * Mem-parsing dan memvalidasi respon JSON dari Google Gemini secara ketat
     */
    public function parseResponse(string $rawBody, int $statusCode, string $url): AiAnalysisResultDTO
    {
        if ($statusCode !== 200) {
            Log::warning('[Gemini AI] Respons HTTP non-200', [
                'status' => $statusCode,
                'body' => $rawBody
            ]);
            return AiAnalysisResultDTO::error('gemini', $this->model, "HTTP Status {$statusCode}: respons tidak berhasil.");
        }

        $decodedResponse = json_decode($rawBody, true);
        $text = $decodedResponse['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (empty($text)) {
            return AiAnalysisResultDTO::error('gemini', $this->model, 'Google Gemini tidak mengembalikan kandidat teks jawaban.');
        }

        // Sanitasi blok markdown ```json jika ada
        $cleanJson = trim($text);
        if (str_starts_with($cleanJson, '```')) {
            $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', $cleanJson);
            $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);
            $cleanJson = trim($cleanJson);
        }

        $data = json_decode($cleanJson, true);
        if (!is_array($data)) {
            return AiAnalysisResultDTO::error('gemini', $this->model, 'Gagal mem-parsing teks keluaran Gemini sebagai JSON terstruktur.');
        }

        // Validasi dan normalisasi kategori
        $rawCategory = strtolower(trim((string)($data['category'] ?? 'unknown')));
        $category = in_array($rawCategory, self::ALLOWED_CATEGORIES, true) ? $rawCategory : 'unknown';

        // Validasi tingkat risiko
        $rawRisk = strtolower(trim((string)($data['risk_level'] ?? 'medium')));
        $riskLevel = in_array($rawRisk, self::ALLOWED_RISK_LEVELS, true) ? $rawRisk : 'medium';

        // Validasi skor risiko (0 - 100)
        $riskScore = isset($data['risk_score']) ? max(0, min(100, (int)$data['risk_score'])) : 50;

        // Validasi confidence (0.00 - 1.00)
        $confidence = isset($data['confidence']) ? max(0.0, min(1.0, (float)$data['confidence'])) : 0.75;

        // Validasi reasoning
        $reasoning = trim((string)($data['reasoning'] ?? 'Tidak ada ringkasan alasan klasifikasi.'));

        // Validasi daftar indikator
        $indicators = [];
        if (isset($data['indicators']) && is_array($data['indicators'])) {
            foreach ($data['indicators'] as $ind) {
                if (is_array($ind) && !empty($ind['name']) && !empty($ind['evidence'])) {
                    $indicators[] = [
                        'name' => (string)$ind['name'],
                        'evidence' => (string)$ind['evidence'],
                        'severity' => in_array(strtolower((string)($ind['severity'] ?? '')), self::ALLOWED_RISK_LEVELS, true)
                            ? strtolower((string)$ind['severity'])
                            : 'medium',
                        'explanation' => (string)($ind['explanation'] ?? '')
                    ];
                }
            }
        }

        return new AiAnalysisResultDTO(
            provider: 'gemini',
            model: $this->model,
            status: 'connected',
            category: $category,
            riskLevel: $riskLevel,
            riskScore: $riskScore,
            indicators: $indicators,
            reasoning: $reasoning,
            confidence: $confidence,
            errorMessage: null,
            rawResponse: $data
        );
    }

    /**
     * Membangun instruksi sistem (System Prompt) spesifik deteksi Judol & Investasi Menyesatkan
     */
    protected function buildSystemPrompt(): string
    {
        return <<<PROMPT
Anda adalah Mesin Analis Kecerdasan Buatan Pertama (Google Gemini) yang bertugas mendeteksi indikasi kejahatan finansial digital.
FOKUS UTAMA SISTEM HANYA PADA DUA KATEGORI ANCAMAN:
1. Judi Online (judol): Taruhan, slot, gacor, maxwin, scatter, jackpot, rtp tinggi, togel, casino, baccarat, deposit pulsa/e-wallet, pola gacor, free spin, withdraw kilat.
2. Investasi Menyesatkan (investasi_menyesatkan): Janji profit pasti harian/mingguan, tanpa risiko, passive income otomatis, penggandaan uang cepat, robot trading tanpa izin OJK/Bappebti, modal kecil untung besar, skema ponzi/arisan bodong.

ATURAN TIGA TINGKAT PENCOCOKAN KATA KUNCI JUDOL (Mencegah False Positive pada Website Biasa):
- Tingkat KUAT (slot gacor, situs togel, casino online, judi slot, bandar togel): Indikator kuat -> Langsung terindikasi judol.
- Tingkat MENENGAH (maxwin, jackpot, scatter, free spin, rtp tinggi): Perlu indikator pendukung (seperti TLD mencurigakan .top/.vip/.xyz, jalur /deposit, angka 777/888) sebelum divonis judol; jika berdiri sendiri pada domain wajar, beri kategori "suspicious".
- Tingkat KONTEKSTUAL (zeus, dewa, thor, dragon, olympus, mahjong): Perlu pemeriksaan tambahan. Jangan menuduh website edukasi/mitologi/umum sebagai "judol" hanya karena memuat kata ini tanpa bukti pendukung perjudian.

Analisis mendalam terhadap:
- Struktur URL, penyamaran karakter, dan nama domain.
- Pola kata kunci dan angka (persentase return tidak realistis, angka 777/888/999).
- Manipulasi psikologis (urgensi waktu, testimoni palsu, jaminan 100%).
- Peniruan lembaga legal (OJK, Bappebti, bank resmi).

ATURAN STRUKTUR RESPON:
Wajib membalas HANYA dalam format JSON murni tanpa pengantar atau penutup Markdown.
Format JSON yang wajib dipatuhi:
{
  "category": "judol" | "investasi_menyesatkan" | "normal" | "suspicious" | "unknown",
  "risk_level": "low" | "medium" | "high" | "critical",
  "risk_score": (integer 0-100),
  "indicators": [
    {
      "name": "nama_indikator_teknis",
      "evidence": "bukti potongan kata / url / angka",
      "severity": "low" | "medium" | "high" | "critical",
      "explanation": "penjelasan singkat indikasi"
    }
  ],
  "reasoning": "penjelasan ringkas alasan klasifikasi",
  "confidence": (float 0.00 sampai 1.00)
}
PROMPT;
    }

    /**
     * Membangun teks user prompt dengan URL dan konteks teknis jaringan
     */
    protected function buildUserPrompt(string $url, array $technicalContext = []): string
    {
        $payload = [
            'target_url' => $url,
            'technical_context' => $technicalContext
        ];

        return "Analisis URL berikut secara cermat:\n" . json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Probe koneksi aman untuk panel admin
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'provider' => 'Google Gemini AI',
                'model' => $this->model,
                'status' => 'Belum Dikonfigurasi',
                'connected' => false,
                'masked_key' => 'Belum diatur (Tambahkan GEMINI_API_KEY di .env)',
                'latency_ms' => null,
                'error' => 'API Key kosong.'
            ];
        }

        $start = microtime(true);
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(6)->post($endpoint, [
                'contents' => [
                    ['parts' => [['text' => 'Ping probe connection. Reply JSON: {"status":"ok"}']]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'max_output_tokens' => 20
                ]
            ]);

            $latency = round((microtime(true) - $start) * 1000);

            if ($response->successful()) {
                return [
                    'provider' => 'Google Gemini AI',
                    'model' => $this->model,
                    'status' => 'Terhubung Aktif',
                    'connected' => true,
                    'masked_key' => $this->getMaskedKey(),
                    'latency_ms' => $latency,
                    'error' => null
                ];
            }

            return [
                'provider' => 'Google Gemini AI',
                'model' => $this->model,
                'status' => 'Respons Gagal (Status ' . $response->status() . ')',
                'connected' => false,
                'masked_key' => $this->getMaskedKey(),
                'latency_ms' => $latency,
                'error' => $response->body()
            ];
        } catch (\Throwable $e) {
            return [
                'provider' => 'Google Gemini AI',
                'model' => $this->model,
                'status' => 'Koneksi Terputus / Error',
                'connected' => false,
                'masked_key' => $this->getMaskedKey(),
                'latency_ms' => null,
                'error' => $e->getMessage()
            ];
        }
    }
}

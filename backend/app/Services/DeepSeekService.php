<?php

namespace App\Services;

use App\DTO\AiAnalysisResultDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service Integrasi DeepSeek AI API
 * Melakukan analisis independen terhadap struktur URL, janji keuntungan, konsistensi bukti, dan modus penipuan.
 * Sesuai Dokumen Spesifikasi Bagian 5.2 & 5.4
 */
class DeepSeekService
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
        $this->apiKey = config('services.deepseek.api_key', env('DEEPSEEK_API_KEY'));
        $this->model = config('services.deepseek.model', env('DEEPSEEK_MODEL', 'deepseek-chat'));
        $this->timeout = (int) config('services.deepseek.timeout', env('DEEPSEEK_TIMEOUT', 20));
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
        $endpoint = "https://api.deepseek.com/chat/completions";

        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($url, $technicalContext);

        $body = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.1
        ];

        return [
            'endpoint' => $endpoint,
            'body' => $body,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json'
            ],
            'timeout' => $this->timeout
        ];
    }

    /**
     * Melakukan analisis independen terhadap URL target
     */
    public function analyzeUrl(string $url, array $technicalContext = []): AiAnalysisResultDTO
    {
        if (!$this->isConfigured()) {
            return AiAnalysisResultDTO::unconfigured('deepseek', $this->model);
        }

        $prepared = $this->prepareRequestPayload($url, $technicalContext);

        try {
            $response = Http::timeout($prepared['timeout'])
                ->withHeaders($prepared['headers'])
                ->post($prepared['endpoint'], $prepared['body']);

            return $this->parseResponse($response->body(), $response->status(), $url);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('[DeepSeek AI] Timeout atau koneksi terputus: ' . $e->getMessage());
            return AiAnalysisResultDTO::error('deepseek', $this->model, 'Koneksi ke DeepSeek API timeout: ' . $e->getMessage(), 'timeout');
        } catch (\Throwable $e) {
            Log::error('[DeepSeek AI] Gagal memanggil API: ' . $e->getMessage());
            return AiAnalysisResultDTO::error('deepseek', $this->model, $e->getMessage(), 'error');
        }
    }

    /**
     * Mem-parsing dan memvalidasi respon JSON dari DeepSeek secara ketat
     */
    public function parseResponse(string $rawBody, int $statusCode, string $url): AiAnalysisResultDTO
    {
        if ($statusCode !== 200) {
            Log::warning('[DeepSeek AI] Respons HTTP non-200', [
                'status' => $statusCode,
                'body' => $rawBody
            ]);
            return AiAnalysisResultDTO::error('deepseek', $this->model, "HTTP Status {$statusCode}: respons tidak berhasil.");
        }

        $decoded = json_decode($rawBody, true);
        $content = $decoded['choices'][0]['message']['content'] ?? null;

        if (empty($content)) {
            return AiAnalysisResultDTO::error('deepseek', $this->model, 'DeepSeek tidak mengembalikan konten jawaban dalam choices[0].');
        }

        // Sanitasi blok markdown ```json jika ada
        $cleanJson = trim($content);
        if (str_starts_with($cleanJson, '```')) {
            $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', $cleanJson);
            $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);
            $cleanJson = trim($cleanJson);
        }

        $data = json_decode($cleanJson, true);
        if (!is_array($data)) {
            return AiAnalysisResultDTO::error('deepseek', $this->model, 'Gagal mem-parsing keluaran DeepSeek sebagai JSON terstruktur.');
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
            provider: 'deepseek',
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
     * Membangun instruksi sistem DeepSeek (Penalaran independen, pembuktian fakta, dan konsistensi)
     */
    protected function buildSystemPrompt(): string
    {
        return <<<PROMPT
Anda adalah Mesin Analis Kecerdasan Buatan Kedua (DeepSeek) yang melakukan verifikasi independen atas tautan digital.
FOKUS ANALISIS HANYA PADA DUA KATEGORI ANCAMAN:
1. Judi Online (judol): Taruhan, slot, pola gacor, maxwin, scatter, jackpot, rtp, togel, casino, deposit/withdraw saldo judi, kode referral bandar judi.
2. Investasi Menyesatkan (investasi_menyesatkan): Janji keuntungan pasti, modal kecil untung berlipat, passive income tanpa risiko, penggandaan saldo kilat, robot trading tanpa izin regulator, arisan piramida.

ATURAN TIGA TINGKAT PENCOCOKAN KATA KUNCI JUDOL (Mencegah False Positive pada Website Biasa):
- Tingkat KUAT (slot gacor, situs togel, casino online, judi slot, bandar togel): Indikator kuat -> Langsung terindikasi judol.
- Tingkat MENENGAH (maxwin, jackpot, scatter, free spin, rtp tinggi): Perlu indikator pendukung (seperti TLD mencurigakan .top/.vip/.xyz, jalur /deposit, angka 777/888) sebelum divonis judol; jika berdiri sendiri pada domain wajar, beri kategori "suspicious".
- Tingkat KONTEKSTUAL (zeus, dewa, thor, dragon, olympus, mahjong): Perlu pemeriksaan tambahan. Jangan menuduh website edukasi/mitologi/umum sebagai "judol" hanya karena memuat kata ini tanpa bukti pendukung perjudian.

Lakukan penalaran independen terhadap:
- Struktur nama domain, typosquatting, dan penyamaran karakter.
- Rasionalitas klaim keuntungan dan perbandingan modal vs imbal hasil.
- Konsistensi bukti dan indikasi rekayasa sosial / manipulasi psikologis.
- Ketiadaan legalitas resmi atau pencatutan izin palsu.

ATURAN STRUKTUR RESPON:
Wajib membalas HANYA format JSON murni tanpa pembuka/penutup markdown.
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
      "explanation": "penjelasan penalaran"
    }
  ],
  "reasoning": "penalaran independen yang jelas atas kesimpulan",
  "confidence": (float 0.00 sampai 1.00)
}
PROMPT;
    }

    protected function buildUserPrompt(string $url, array $technicalContext = []): string
    {
        $payload = [
            'target_url' => $url,
            'technical_context' => $technicalContext
        ];

        return "Verifikasi independen untuk tautan berikut:\n" . json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Audit Forensik Siber untuk Laporan Pengaduan Warga (Deep Reasoning)
     * Tetap dipertahankan untuk kebutuhan modul Lapor Siber
     */
    public function analyzeReport(array $report): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $url = "https://api.deepseek.com/chat/completions";

        $systemPrompt = <<<PROMPT
Anda adalah Auditor Forensik Siber Aswaja CyberShield.
Tugas Anda adalah menelaah laporan aduan warga terkait kejahatan siber (judi online, phishing, investasi bodong, pembajakan akun).
Lakukan penalaran mendalam (deep reasoning) terhadap narasi kronologi, modus penipuan, dan bukti URL.

Wajib membalas HANYA dalam format JSON dengan struktur:
{
  "riskLevel": ("Rendah" | "Sedang" | "Tinggi" | "Kritis"),
  "modusOperandi": (penjelasan singkat pola kejahatan / manipulasi korban),
  "legalViolation": (pelanggaran UU ITE / Fatwa MUI / Regulasi OJK),
  "recommendedAction": (tindakan yang harus diambil admin: misal blokir DNS, koordinasi satgas judol, lapor bank),
  "triageNote": (catatan verifikasi resmi untuk dimasukkan ke sistem admin)
}
PROMPT;

        $payload = json_encode([
            'target_url' => $report['url'] ?? '',
            'kategori_aduan' => $report['category'] ?? '',
            'kronologi_kejadian' => $report['description'] ?? 'Tidak ada kronologi',
            'pelapor' => $report['reporter'] ?? 'Anonim'
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout($this->timeout)
                ->post($url, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "Data laporan warga:\n" . $payload]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.1
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $data = json_decode($content, true);
                if (is_array($data) && isset($data['riskLevel'])) {
                    return $data;
                }
            } else {
                Log::warning('[DeepSeek AI] Respons non-200', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('[DeepSeek AI] Gagal menganalisis laporan: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Probe koneksi aman untuk panel admin
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'provider' => 'DeepSeek AI',
                'model' => $this->model,
                'status' => 'Belum Dikonfigurasi',
                'connected' => false,
                'masked_key' => 'Belum diatur (Tambahkan DEEPSEEK_API_KEY di .env)',
                'latency_ms' => null,
                'error' => 'API Key kosong.'
            ];
        }

        $start = microtime(true);
        $apiUrl = "https://api.deepseek.com/chat/completions";

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(6)
                ->post($apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'user', 'content' => 'Ping connection test. Reply json {"status":"ok"}']
                    ],
                    'max_tokens' => 20
                ]);

            $latency = round((microtime(true) - $start) * 1000);

            if ($response->successful()) {
                return [
                    'provider' => 'DeepSeek AI',
                    'model' => $this->model,
                    'status' => 'Terhubung Aktif',
                    'connected' => true,
                    'masked_key' => $this->getMaskedKey(),
                    'latency_ms' => $latency,
                    'error' => null
                ];
            }

            return [
                'provider' => 'DeepSeek AI',
                'model' => $this->model,
                'status' => 'Respons Gagal (Status ' . $response->status() . ')',
                'connected' => false,
                'masked_key' => $this->getMaskedKey(),
                'latency_ms' => $latency,
                'error' => $response->body()
            ];
        } catch (\Throwable $e) {
            return [
                'provider' => 'DeepSeek AI',
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

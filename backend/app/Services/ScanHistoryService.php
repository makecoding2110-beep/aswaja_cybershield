<?php

namespace App\Services;

use App\DTO\AiAnalysisResultDTO;
use App\DTO\AiComparisonDTO;
use App\DTO\RiskAssessmentDTO;
use App\DTO\UrlAnalysisDTO;
use App\Models\LinkScan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service Pengelolaan Riwayat Audit Pemeriksaan Tautan (Scan History)
 * Menyimpan data hasil pemeriksaan secara terpisah (Suricata & Gemini AI)
 * Sesuai Dokumen Spesifikasi Bagian 9 & Bagian 10
 */
class ScanHistoryService
{
    /**
     * Cache sementara di memori jika koneksi basis data belum terhubung
     */
    protected static array $inMemoryHistory = [];

    /**
     * Menyimpan riwayat audit pemeriksaan tautan lengkap
     */
    public function recordScan(
        UrlAnalysisDTO $urlAnalysis,
        array $suricataResult,
        AiAnalysisResultDTO $gemini,
        ?AiAnalysisResultDTO $secondaryAi = null,
        ?AiComparisonDTO $aiComparison = null,
        ?RiskAssessmentDTO $riskAssessment = null
    ): array {
        $id = 'SCN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $rawFinalCat = $riskAssessment ? $riskAssessment->finalCategory : 'normal';
        $validDbCategories = ['terindikasi_judol', 'terindikasi_investasi', 'mencurigakan', 'normal', 'belum_dapat_disimpulkan'];
        $dbFinalCategory = in_array($rawFinalCat, $validDbCategories, true)
            ? $rawFinalCat
            : ($rawFinalCat === 'belum_ditemukan_indikasi' || $rawFinalCat === 'aman' ? 'normal' : 'mencurigakan');

        $rawRiskLevel = $riskAssessment ? $riskAssessment->riskLevel : 'rendah';
        $dbRiskLevel = match (strtolower((string) $rawRiskLevel)) {
            'berat', 'kritis', 'critical' => 'kritis',
            'tinggi', 'high' => 'tinggi',
            'sedang', 'medium' => 'sedang',
            default => 'rendah'
        };

        $recordData = [
            'id' => $id,
            'original_url' => $urlAnalysis->originalUrl,
            'normalized_url' => $urlAnalysis->normalizedUrl,
            'domain' => $urlAnalysis->domain,
            'scanned_at' => now()->toIso8601String(),

            // Hasil masing-masing engine disimpan terpisah
            'suricata_result' => [
                'status' => $suricataResult['status'] ?? 'tidak_tersedia',
                'available' => $suricataResult['available'] ?? false,
                'matched' => $suricataResult['matched'] ?? false,
                'alerts_count' => $suricataResult['alerts_count'] ?? 0,
                'alerts' => $suricataResult['alerts'] ?? [],
                'indicators' => $suricataResult['indicators'] ?? []
            ],
            'gemini_result' => $gemini->toArray(),
            'ai_comparison' => $aiComparison ? $aiComparison->toArray() : null,

            // Indikator gabungan & teknik terdeteksi
            'indicators' => $riskAssessment ? $riskAssessment->allIndicators : [],
            'techniques' => $riskAssessment ? ($riskAssessment->techniques ?? []) : [],

            // Keputusan akhir
            'final_category' => $dbFinalCategory,
            'risk_level' => $dbRiskLevel,
            'risk_score' => $riskAssessment ? $riskAssessment->riskScore : 0,
            'classification_reason' => $riskAssessment ? $riskAssessment->reasoning : '',
            'decision_source' => $riskAssessment ? $riskAssessment->decisionSource : 'clean_consensus',
            'scan_status' => (!$gemini->isSuccess() && (!($suricataResult['available'] ?? false)))
                ? 'partial'
                : 'completed',
        ];

        // Simpan ke PostgreSQL Supabase jika tabel link_scans sudah tersedia
        // ATURAN: Jika tautan yang di-scan sama berulang-ulang, JANGAN ditambahkan lagi ke database. Cukup 1 kali jika itu tautan baru.
        try {
            if ($this->isDatabaseReady()) {
                $cleanNorm = rtrim($recordData['normalized_url'], '/');
                $noProto = preg_replace('#^https?://(www\.)?#i', '', $cleanNorm);

                $existing = LinkScan::where('normalized_url', $recordData['normalized_url'])
                    ->orWhere('normalized_url', $cleanNorm)
                    ->orWhere('normalized_url', $cleanNorm . '/')
                    ->orWhere('original_url', $recordData['original_url'])
                    ->orWhere('original_url', rtrim($recordData['original_url'], '/'))
                    ->orWhere('normalized_url', 'http://' . $noProto)
                    ->orWhere('normalized_url', 'https://' . $noProto)
                    ->orWhere('normalized_url', 'http://' . $noProto . '/')
                    ->orWhere('normalized_url', 'https://' . $noProto . '/')
                    ->orWhere('normalized_url', 'http://www.' . $noProto)
                    ->orWhere('normalized_url', 'https://www.' . $noProto)
                    ->orWhere('normalized_url', 'http://www.' . $noProto . '/')
                    ->orWhere('normalized_url', 'https://www.' . $noProto . '/')
                    ->first();

                if ($existing) {
                    // Tautan lama sudah ada: JANGAN ditambahkan / duplikasi ke database
                    return $existing->toArray();
                }

                // Tautan baru: tambahkan cukup 1 kali saja
                LinkScan::create($recordData);
            } else {
                $cleanNorm = rtrim($recordData['normalized_url'], '/');
                $noProto = preg_replace('#^https?://(www\.)?#i', '', $cleanNorm);
                foreach (self::$inMemoryHistory as $mem) {
                    $memNorm = rtrim($mem['normalized_url'] ?? '', '/');
                    $memNoProto = preg_replace('#^https?://(www\.)?#i', '', $memNorm);
                    if ($memNorm === $cleanNorm || $memNoProto === $noProto) {
                        return $mem;
                    }
                }
                self::$inMemoryHistory[$id] = $recordData;
            }
        } catch (\Throwable $e) {
            Log::warning('[ScanHistory] Gagal menyimpan ke database, beralih ke in-memory: ' . $e->getMessage());
            self::$inMemoryHistory[$id] = $recordData;
        }

        // Simpan juga ke storage/app/mock_database.json (JANGAN duplikasi jika tautan sudah ada)
        try {
            $mockPath = storage_path('app/mock_database.json');
            if (file_exists($mockPath)) {
                $mockData = json_decode(file_get_contents($mockPath), true) ?? [];
                $existingScans = $mockData['link_scans'] ?? [];
                $cleanNorm = rtrim($recordData['normalized_url'], '/');
                $noProto = preg_replace('#^https?://(www\.)?#i', '', $cleanNorm);

                $alreadyExists = false;
                foreach ($existingScans as $s) {
                    $sNorm = rtrim($s['normalized_url'] ?? '', '/');
                    $sNoProto = preg_replace('#^https?://(www\.)?#i', '', $sNorm);
                    if ($sNorm === $cleanNorm || $sNoProto === $noProto || rtrim($s['original_url'] ?? '', '/') === rtrim($recordData['original_url'], '/')) {
                        $alreadyExists = true;
                        break;
                    }
                }

                if (!$alreadyExists) {
                    array_unshift($existingScans, $recordData);
                    $mockData['link_scans'] = $existingScans;
                    file_put_contents($mockPath, json_encode($mockData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                }
            }
        } catch (\Throwable $e) {
            // Abaikan kesalahan penulisan cache lokal
        }

        return $recordData;
    }

    /**
     * Mengambil riwayat audit berdasarkan ID
     */
    public function findById(string $id): ?array
    {
        try {
            if ($this->isDatabaseReady()) {
                $model = LinkScan::find($id);
                if ($model) {
                    return $model->toArray();
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[ScanHistory] Gagal query DB: ' . $e->getMessage());
        }

        return self::$inMemoryHistory[$id] ?? null;
    }

    /**
     * Mengambil daftar riwayat pemeriksaan terbaru
     */
    public function getRecentScans(int $limit = 20): array
    {
        try {
            if ($this->isDatabaseReady()) {
                return LinkScan::orderBy('scanned_at', 'desc')->limit($limit)->get()->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[ScanHistory] Gagal query DB: ' . $e->getMessage());
        }

        $list = array_values(self::$inMemoryHistory);
        usort($list, fn($a, $b) => strcmp($b['scanned_at'], $a['scanned_at']));

        return array_slice($list, 0, $limit);
    }

    /**
     * Memeriksa apakah tabel link_scans sudah ada di database aktif
     */
    protected function isDatabaseReady(): bool
    {
        try {
            return DB::connection()->getSchemaBuilder()->hasTable('link_scans');
        } catch (\Throwable $e) {
            return false;
        }
    }
}


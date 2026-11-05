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
 * Menyimpan data hasil pemeriksaan secara terpisah (Suricata, Gemini, DeepSeek)
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
        AiAnalysisResultDTO $deepseek,
        AiComparisonDTO $aiComparison,
        RiskAssessmentDTO $riskAssessment
    ): array {
        $id = 'SCN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

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
            'deepseek_result' => $deepseek->toArray(),
            'ai_comparison' => $aiComparison->toArray(),

            // Indikator gabungan
            'indicators' => $riskAssessment->allIndicators,

            // Keputusan akhir
            'final_category' => $riskAssessment->finalCategory,
            'risk_level' => $riskAssessment->riskLevel,
            'risk_score' => $riskAssessment->riskScore,
            'classification_reason' => $riskAssessment->reasoning,
            'decision_source' => $riskAssessment->decisionSource,
            'scan_status' => (!$gemini->isSuccess() && !$deepseek->isSuccess() && !($suricataResult['available'] ?? false))
                ? 'partial'
                : 'completed',
        ];

        // Simpan ke SQLite/PostgreSQL jika tabel link_scans sudah tersedia
        try {
            if ($this->isDatabaseReady()) {
                LinkScan::create($recordData);
            } else {
                self::$inMemoryHistory[$id] = $recordData;
            }
        } catch (\Throwable $e) {
            Log::warning('[ScanHistory] Gagal menyimpan ke database, beralih ke in-memory: ' . $e->getMessage());
            self::$inMemoryHistory[$id] = $recordData;
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


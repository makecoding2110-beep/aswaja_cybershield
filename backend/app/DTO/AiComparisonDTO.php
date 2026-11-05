<?php

namespace App\DTO;

/**
 * Data Transfer Object untuk hasil perbandingan independen Dual AI (Gemini vs DeepSeek)
 * Sesuai Dokumen Spesifikasi Bagian 5.3 & Bagian 6
 */
class AiComparisonDTO
{
    /**
     * @param bool $bothAvailable Apakah kedua AI aktif dan berhasil memberikan keputusan
     * @param bool $isMatch Apakah kategori Gemini dan DeepSeek identik
     * @param string|null $agreedCategory Kategori yang disepakati jika match
     * @param int $scoreDelta Selisih nilai risiko kedua model (abs(gemini - deepseek))
     * @param bool $hasDiscrepancy Apakah terdapat pertentangan klasifikasi
     * @param array $discrepancyDetails Catatan rincian perbedaan jika ada
     * @param array $combinedIndicators Gabungan indikator temuan dari kedua AI
     * @param string $summary Narasi ringkas perbandingan hasil kedua AI
     */
    public function __construct(
        public readonly bool $bothAvailable,
        public readonly bool $isMatch,
        public readonly ?string $agreedCategory,
        public readonly int $scoreDelta,
        public readonly bool $hasDiscrepancy,
        public readonly array $discrepancyDetails,
        public readonly array $combinedIndicators,
        public readonly string $summary
    ) {}

    public function toArray(): array
    {
        return [
            'both_available' => $this->bothAvailable,
            'is_match' => $this->isMatch,
            'agreed_category' => $this->agreedCategory,
            'score_delta' => $this->scoreDelta,
            'has_discrepancy' => $this->hasDiscrepancy,
            'discrepancy_details' => $this->discrepancyDetails,
            'combined_indicators' => $this->combinedIndicators,
            'summary' => $this->summary,
        ];
    }
}


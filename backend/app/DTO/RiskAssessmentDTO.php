<?php

namespace App\DTO;

/**
 * Data Transfer Object untuk hasil penilaian risiko akhir (Risk Assessment)
 * Menggabungkan temuan teknis URL, Suricata NIDS, dan Dual AI
 * Sesuai Dokumen Spesifikasi Bagian 6 & 7
 */
class RiskAssessmentDTO
{
    /**
     * @param string $finalCategory Kategori hasil: 'normal' | 'mencurigakan' | 'terindikasi_judol' | 'terindikasi_investasi' | 'belum_dapat_disimpulkan'
     * @param string $categoryLabel Label bahasa Indonesia ramah pengguna
     * @param string $riskLevel Tingkat risiko: 'rendah' | 'sedang' | 'tinggi' | 'kritis'
     * @param int $riskScore Nilai risiko terstandar (0 - 100)
     * @param string $alertClass Kelas styling: 'emerald' | 'amber' | 'red' | 'slate'
     * @param string $title Judul kesimpulan analisis
     * @param string $reasoning Narasi penjelasan komprehensif dasar klasifikasi
     * @param array $allIndicators Seluruh daftar bukti indikator gabungan terstruktur
     * @param array $matrixTrace Jejak evaluasi matriks (Suricata, Gemini, DeepSeek)
     * @param string $decisionSource Sumber penentu utama ('suricata_ai_consensus' | 'suricata_ids' | 'dual_ai_only' | 'unconcluded')
     */
    public function __construct(
        public readonly string $finalCategory,
        public readonly string $categoryLabel,
        public readonly string $riskLevel,
        public readonly int $riskScore,
        public readonly string $alertClass,
        public readonly string $title,
        public readonly string $reasoning,
        public readonly array $allIndicators,
        public readonly array $matrixTrace,
        public readonly string $decisionSource
    ) {}

    public function toArray(): array
    {
        return [
            'final_category' => $this->finalCategory,
            'category_label' => $this->categoryLabel,
            'risk_level' => $this->riskLevel,
            'risk_score' => $this->riskScore,
            'alert_class' => $this->alertClass,
            'title' => $this->title,
            'reasoning' => $this->reasoning,
            'indicators' => $this->allIndicators,
            'matrix_trace' => $this->matrixTrace,
            'decision_source' => $this->decisionSource,
        ];
    }
}


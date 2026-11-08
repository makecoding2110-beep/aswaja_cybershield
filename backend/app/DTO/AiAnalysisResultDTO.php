<?php

namespace App\DTO;

/**
 * Data Transfer Object untuk hasil analisis AI independen (Google Gemini AI)
 * Sesuai Dokumen Spesifikasi Bagian 5.4 & Bagian 10
 */
class AiAnalysisResultDTO
{
    /**
     * @param string $provider Nama provider ('gemini')
     * @param string $model Nama model yang digunakan
     * @param string $status Status koneksi/hasil ('connected' | 'unconfigured' | 'error' | 'timeout')
     * @param string|null $category Kategori: 'judol' | 'investasi_menyesatkan' | 'normal' | 'suspicious' | 'unknown'
     * @param string|null $riskLevel Tingkat risiko: 'low' | 'medium' | 'high' | 'critical'
     * @param int $riskScore Skor risiko 0 - 100
     * @param array $indicators Array indikator terstruktur: [['name' => ..., 'evidence' => ..., 'severity' => ..., 'explanation' => ...]]
     * @param string $reasoning Alasan naratif klasifikasi
     * @param float $confidence Tingkat kepercayaan 0.00 - 1.00
     * @param string|null $errorMessage Pesan kendala jika terjadi error
     * @param array|null $rawResponse Data respon asli dari AI untuk keperluan audit
     */
    public function __construct(
        public readonly string $provider,
        public readonly string $model,
        public readonly string $status,
        public readonly ?string $category,
        public readonly ?string $riskLevel,
        public readonly int $riskScore,
        public readonly array $indicators,
        public readonly string $reasoning,
        public readonly float $confidence,
        public readonly ?string $errorMessage = null,
        public readonly ?array $rawResponse = null,
        public readonly array $techniques = []
    ) {}

    /**
     * Factory untuk membuat status AI yang belum dikonfigurasi (No fake simulation)
     */
    public static function unconfigured(string $provider, string $model): self
    {
        return new self(
            provider: $provider,
            model: $model,
            status: 'unconfigured',
            category: null,
            riskLevel: null,
            riskScore: 0,
            indicators: [],
            reasoning: "Layanan {$provider} belum terhubung (API Key belum dikonfigurasi di environment).",
            confidence: 0.0,
            errorMessage: 'API Key belum diatur di .env',
            rawResponse: null
        );
    }

    /**
     * Factory untuk membuat status AI yang mengalami error/timeout
     */
    public static function error(string $provider, string $model, string $message, string $status = 'error'): self
    {
        return new self(
            provider: $provider,
            model: $model,
            status: $status,
            category: null,
            riskLevel: null,
            riskScore: 0,
            indicators: [],
            reasoning: "Gagal memproses analisis {$provider}: {$message}",
            confidence: 0.0,
            errorMessage: $message,
            rawResponse: null
        );
    }

    /**
     * Memeriksa apakah analisis berhasil menghasilkan keputusan valid
     */
    public function isSuccess(): bool
    {
        return $this->status === 'connected' && $this->category !== null;
    }

    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'model' => $this->model,
            'status' => $this->status,
            'category' => $this->category,
            'risk_level' => $this->riskLevel,
            'risk_score' => $this->riskScore,
            'indicators' => $this->indicators,
            'techniques' => $this->techniques,
            'reasoning' => $this->reasoning,
            'confidence' => $this->confidence,
            'error_message' => $this->errorMessage,
            'is_success' => $this->isSuccess(),
        ];
    }
}


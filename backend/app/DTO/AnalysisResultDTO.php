<?php

namespace App\DTO;

class AnalysisResultDTO
{
    public function __construct(
        public readonly string $url,
        public readonly string $category, // terindikasi_judol | perlu_diwaspadai | belum_ditemukan_indikasi | gagal_dianalisis
        public readonly int $score,
        public readonly string $statusAlert,
        public readonly string $alertClass, // red | amber | emerald | slate
        public readonly string $title,
        public readonly array $findings,
        public readonly string $scannedAt,
        public readonly string $source = 'gemini_ai', // gemini_ai | heuristic_engine
        public readonly ?int $riskScore = null,
        public readonly ?int $safetyScore = null,
        public readonly ?string $finalCategory = null,
        public readonly array $techniques = []
    ) {}

    public function toArray(): array
    {
        return [
            'success' => true,
            'url' => $this->url,
            'category' => $this->category,
            'final_category' => $this->finalCategory ?? $this->category,
            'score' => $this->score,
            'risk_score' => $this->riskScore ?? (100 - $this->score),
            'safety_score' => $this->safetyScore ?? $this->score,
            'statusAlert' => $this->statusAlert,
            'alertClass' => $this->alertClass,
            'title' => $this->title,
            'findings' => $this->findings,
            'techniques' => $this->techniques,
            'scannedAt' => $this->scannedAt,
            'source' => $this->source,
        ];
    }
}


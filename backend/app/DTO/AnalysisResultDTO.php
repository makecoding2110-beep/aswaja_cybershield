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
        public readonly string $source = 'dual_ai' // gemini_ai | deepseek_ai | heuristic_engine
    ) {}

    public function toArray(): array
    {
        return [
            'success' => true,
            'url' => $this->url,
            'category' => $this->category,
            'score' => $this->score,
            'statusAlert' => $this->statusAlert,
            'alertClass' => $this->alertClass,
            'title' => $this->title,
            'findings' => $this->findings,
            'scannedAt' => $this->scannedAt,
            'source' => $this->source,
        ];
    }
}


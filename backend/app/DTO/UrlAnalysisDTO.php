<?php

namespace App\DTO;

/**
 * Data Transfer Object untuk hasil analisis teknis struktur URL
 * Berdasarkan Spesifikasi Bagian 3.1 & 3.4.B
 */
class UrlAnalysisDTO
{
    /**
     * @param string $originalUrl URL mentah sebelum normalisasi
     * @param string $normalizedUrl URL bersih setelah normalisasi
     * @param string $scheme Protokol (http/https)
     * @param string $host Hostname (misal: ojk-investasi.xyz)
     * @param string $domain Domain utama terdaftar
     * @param string|null $subdomain Subdomain jika ada
     * @param int|null $port Port jaringan
     * @param string $path Path URL
     * @param array $queryParams Parameter query string
     * @param bool $isDirectIp Indikasi apakah host merupakan IP langsung
     * @param bool $isShortener Indikasi penggunaan URL shortener
     * @param bool $isSuspiciousTld Indikasi penggunaan TLD berisiko
     * @param bool $hasTyposquatting Indikasi typosquatting terhadap lembaga resmi
     * @param array $indicators Daftar temuan indikator teknis terstruktur
     * @param array $metrics Metrik statistik struktural URL
     */
    public function __construct(
        public readonly string $originalUrl,
        public readonly string $normalizedUrl,
        public readonly string $scheme,
        public readonly string $host,
        public readonly string $domain,
        public readonly ?string $subdomain,
        public readonly ?int $port,
        public readonly string $path,
        public readonly array $queryParams,
        public readonly bool $isDirectIp,
        public readonly bool $isShortener,
        public readonly bool $isSuspiciousTld,
        public readonly bool $hasTyposquatting,
        public readonly array $indicators,
        public readonly array $metrics = []
    ) {}

    public function toArray(): array
    {
        return [
            'original_url' => $this->originalUrl,
            'normalized_url' => $this->normalizedUrl,
            'scheme' => $this->scheme,
            'host' => $this->host,
            'domain' => $this->domain,
            'subdomain' => $this->subdomain,
            'port' => $this->port,
            'path' => $this->path,
            'query_params' => $this->queryParams,
            'is_direct_ip' => $this->isDirectIp,
            'is_shortener' => $this->isShortener,
            'is_suspicious_tld' => $this->isSuspiciousTld,
            'has_typosquatting' => $this->hasTyposquatting,
            'indicators' => $this->indicators,
            'metrics' => $this->metrics,
        ];
    }
}


<?php

namespace App\Services;

use App\DTO\UrlAnalysisDTO;
use InvalidArgumentException;

/**
 * Service Validasi, Normalisasi, dan Analisis Struktur URL
 * Mengimplementasikan Spesifikasi Bagian 3.1 & 3.4.B
 */
class UrlValidationService
{
    /**
     * Daftar TLD (Top-Level Domain) yang sering disalahgunakan untuk sindikat judol & penipuan
     */
    protected const SUSPICIOUS_TLDS = [
        'xyz', 'top', 'cc', 'vip', 'fun', 'click', 'site', 'live', 'club',
        'icu', 'monster', 'cfd', 'rest', 'sbs', 'buzz', 'bet', 'casino',
        'lat', 'pw', 'win', 'tokyo', 'casa', 'link', 'work', 'date', 'trade'
    ];

    /**
     * Layanan Pemendek Tautan (URL Shortener) Populer
     */
    protected const URL_SHORTENERS = [
        'bit.ly', 'tinyurl.com', 't.co', 's.id', 'cutt.ly', 'shorturl.at',
        'is.gd', 'rb.gy', 'linktr.ee', 'tribun.id', 'wa.me', 't.me'
    ];

    /**
     * Database Entitas Lembaga Resmi & Platform Terdaftar untuk Deteksi Typosquatting/Impersonasi
     */
    protected const OFFICIAL_BRANDS = [
        'ojk' => [
            'name' => 'Otoritas Jasa Keuangan (OJK)',
            'official_domains' => ['ojk.go.id']
        ],
        'bappebti' => [
            'name' => 'Bappebti Kementerian Perdagangan',
            'official_domains' => ['bappebti.go.id']
        ],
        'bi' => [
            'name' => 'Bank Indonesia (BI)',
            'official_domains' => ['bi.go.id']
        ],
        'kominfo' => [
            'name' => 'Kementerian Komunikasi dan Digital (Komdigi/Kominfo)',
            'official_domains' => ['kominfo.go.id', 'komdigi.go.id']
        ],
        'bca' => [
            'name' => 'Bank Central Asia (BCA)',
            'official_domains' => ['bca.co.id', 'klikbca.co.id']
        ],
        'mandiri' => [
            'name' => 'Bank Mandiri',
            'official_domains' => ['bankmandiri.co.id', 'livinbymandiri.co.id']
        ],
        'bri' => [
            'name' => 'Bank Rakyat Indonesia (BRI)',
            'official_domains' => ['bri.co.id', 'brimo.bri.co.id']
        ],
        'bni' => [
            'name' => 'Bank Negara Indonesia (BNI)',
            'official_domains' => ['bni.co.id', 'wondr.bni.co.id']
        ],
        'bareksa' => [
            'name' => 'Bareksa Marketplace Investasi',
            'official_domains' => ['bareksa.com']
        ],
        'bibit' => [
            'name' => 'Bibit Tumbuh Bersama',
            'official_domains' => ['bibit.id']
        ],
        'ajaib' => [
            'name' => 'Ajaib Sekuritas / Kripto',
            'official_domains' => ['ajaib.co.id']
        ],
        'pluang' => [
            'name' => 'Pluang Investasi',
            'official_domains' => ['pluang.com']
        ],
        'indodax' => [
            'name' => 'Indodax',
            'official_domains' => ['indodax.com']
        ]
    ];

    /**
     * Normalisasi URL mentah dan validasi keamanan awal (SSRF Guard)
     */
    public function normalize(string $rawUrl): string
    {
        $url = trim($rawUrl);

        if ($url === '') {
            throw new InvalidArgumentException('URL tautan tidak boleh kosong.');
        }

        // Tambahkan skema https:// jika belum ada
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        // Validasi sintaks URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Format sintaks URL tidak valid.');
        }

        $parsed = parse_url($url);
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidArgumentException('Hanya protokol HTTP dan HTTPS yang didukung.');
        }

        $host = strtolower($parsed['host'] ?? '');
        if ($host === '') {
            throw new InvalidArgumentException('Host target URL tidak valid.');
        }

        // SSRF Guard: Cegah akses loopback, private IP, dan metadata internal
        $this->enforceSsrfGuard($host);

        // Rekonstruksi URL terstandar (lowercase host, trim port default)
        $normalized = $scheme . '://' . $host;
        if (isset($parsed['port'])) {
            $port = (int)$parsed['port'];
            if (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443)) {
                $normalized .= ':' . $port;
            }
        }

        $path = $parsed['path'] ?? '/';
        if ($path === '') {
            $path = '/';
        }
        $normalized .= $path;

        if (isset($parsed['query']) && $parsed['query'] !== '') {
            $normalized .= '?' . $parsed['query'];
        }

        return $normalized;
    }

    /**
     * Menganalisis struktur URL secara menyeluruh berdasarkan indikator teknis
     */
    public function analyze(string $rawUrl): UrlAnalysisDTO
    {
        $normalizedUrl = $this->normalize($rawUrl);
        $parsed = parse_url($normalizedUrl);

        $scheme = strtolower($parsed['scheme'] ?? 'https');
        $host = strtolower($parsed['host'] ?? '');
        $port = isset($parsed['port']) ? (int)$parsed['port'] : null;
        $path = $parsed['path'] ?? '/';

        $queryParams = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $queryParams);
        }

        $domainInfo = $this->extractDomainParts($host);
        $domain = $domainInfo['domain'];
        $subdomain = $domainInfo['subdomain'];
        $tld = $domainInfo['tld'];

        $isDirectIp = (bool) filter_var($host, FILTER_VALIDATE_IP);
        $isShortener = in_array($host, self::URL_SHORTENERS, true);
        $isSuspiciousTld = in_array($tld, self::SUSPICIOUS_TLDS, true);

        $indicators = [];
        $metrics = [
            'url_length' => strlen($normalizedUrl),
            'domain_length' => strlen($host),
            'hyphen_count' => substr_count($host, '-'),
            'digit_count' => preg_match_all('/\d/', $host),
            'tld' => $tld,
        ];

        // 1. Pemeriksaan Penggunaan Alamat IP Langsung
        if ($isDirectIp) {
            $indicators[] = [
                'name' => 'direct_ip_usage',
                'evidence' => $host,
                'severity' => 'high',
                'explanation' => 'Menggunakan alamat IP langsung tanpa nama domain, pola umum situs penipuan dan bypass sensor DNS.'
            ];
        }

        // 2. Pemeriksaan Penggunaan URL Shortener
        if ($isShortener) {
            $indicators[] = [
                'name' => 'url_shortener',
                'evidence' => $host,
                'severity' => 'medium',
                'explanation' => 'Menggunakan pemendek tautan untuk menyamarkan alamat tujuan asli.'
            ];
        }

        // 3. Pemeriksaan Ekstensi Domain (TLD) Berisiko
        if ($isSuspiciousTld) {
            $indicators[] = [
                'name' => 'suspicious_tld',
                'evidence' => '.' . $tld,
                'severity' => 'medium',
                'explanation' => "Ekstensi domain (TLD .{$tld}) kerap dimanfaatkan oleh sindikat perjudian dan investasi ilegal karena minim regulasi verifikasi identitas."
            ];
        }

        // 4. Deteksi Typosquatting dan Peniruan Lembaga Resmi
        $typosquattingFindings = $this->detectTyposquatting($host, $domain);
        $hasTyposquatting = !empty($typosquattingFindings);
        foreach ($typosquattingFindings as $finding) {
            $indicators[] = $finding;
        }

        // 5. Analisis Huruf Berulang (Repetitive Characters)
        // Mendeteksi 3+ karakter berulang (misal: slooot, gacooor) atau 2 karakter berulang tidak wajar (misal: investasii, cuannn)
        // Abaikan prefix standar web seperti 'www.' agar tidak memicu false positive
        $cleanHostForRepetition = preg_replace('/^www\d*\./i', '', $host);
        if (preg_match('/([a-zA-Z])\1{2,}|(ii|uu|jj|vv|qq|yy)|([a-z])\3(?=\d{2,}|[-_.]|$)/i', $cleanHostForRepetition, $m)) {
            $matchedEvidence = $m[0];
            if (strtolower($matchedEvidence) !== 'www') {
                $indicators[] = [
                    'name' => 'repetitive_characters',
                    'evidence' => $matchedEvidence,
                    'severity' => 'medium',
                    'explanation' => "Domain menggunakan pengulangan karakter tidak wajar ('{$matchedEvidence}'), tipikal typosquatting atau penyamaran domain."
                ];
            }
        }

        // 6. Analisis Angka Mencurigakan pada URL (777, 888, 999, tahun terkini, urutan angka acak)
        $numberFindings = $this->detectSuspiciousNumbers($normalizedUrl, $host);
        foreach ($numberFindings as $finding) {
            $indicators[] = $finding;
        }

        // 7. Penggunaan Tanda Hubung (Hyphens) Berlebihan
        if ($metrics['hyphen_count'] >= 2) {
            $indicators[] = [
                'name' => 'excessive_hyphens',
                'evidence' => "Jumlah tanda hubung: {$metrics['hyphen_count']}",
                'severity' => $metrics['hyphen_count'] >= 3 ? 'high' : 'medium',
                'explanation' => 'Penggunaan tanda hubung berlebihan pada domain bertujuan merangkai kata kunci promosi atau mengelabui filter merek.'
            ];
        }

        // 8. URL Terlalu Panjang
        if ($metrics['url_length'] > 120 || $metrics['domain_length'] > 32) {
            $indicators[] = [
                'name' => 'url_too_long',
                'evidence' => "Panjang URL: {$metrics['url_length']} karakter",
                'severity' => 'low',
                'explanation' => 'Struktur URL sangat panjang tidak wajar, berpotensi menyembunyikan parameter berbahaya.'
            ];
        }

        // 9. Subdomain Mencurigakan / Bertingkat
        if (!empty($subdomain)) {
            $subParts = explode('.', $subdomain);
            if (count($subParts) >= 2) {
                $indicators[] = [
                    'name' => 'excessive_subdomains',
                    'evidence' => $subdomain,
                    'severity' => 'medium',
                    'explanation' => 'Penggunaan subdomain bertingkat banyak kerap dipakai menyamarkan domain induk penyedia hosting.'
                ];
            }
            if (preg_match('/(secure|login|verify|update|account|auth|ojk|bappebti)/i', $subdomain, $subMatch)) {
                $indicators[] = [
                    'name' => 'deceptive_subdomain',
                    'evidence' => $subMatch[0],
                    'severity' => 'high',
                    'explanation' => "Subdomain menggunakan istilah meyakinkan ('{$subMatch[0]}') untuk memberikan kesan otentikasi resmi palsu."
                ];
            }
        }

        // 10. Parameter URL Mencurigakan (Referral, Deposit, Promo, Agent, dsb)
        $paramFindings = $this->detectSuspiciousParams($queryParams, $path);
        foreach ($paramFindings as $finding) {
            $indicators[] = $finding;
        }

        // 11. Analisis Kata Kunci Judi Online Berjenjang 3 Tingkat (Kuat, Menengah, Kontekstual)
        $judolTierFindings = $this->detectJudolKeywordTiers($normalizedUrl);
        foreach ($judolTierFindings as $finding) {
            $indicators[] = $finding;
        }

        return new UrlAnalysisDTO(
            originalUrl: $rawUrl,
            normalizedUrl: $normalizedUrl,
            scheme: $scheme,
            host: $host,
            domain: $domain,
            subdomain: $subdomain,
            port: $port,
            path: $path,
            queryParams: $queryParams,
            isDirectIp: $isDirectIp,
            isShortener: $isShortener,
            isSuspiciousTld: $isSuspiciousTld,
            hasTyposquatting: $hasTyposquatting,
            indicators: $indicators,
            metrics: $metrics
        );
    }

    /**
     * Memisahkan domain, subdomain, dan ekstensi TLD
     */
    protected function extractDomainParts(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return ['domain' => $host, 'subdomain' => null, 'tld' => ''];
        }

        $parts = explode('.', $host);
        $count = count($parts);

        if ($count <= 1) {
            return ['domain' => $host, 'subdomain' => null, 'tld' => ''];
        }

        // Penanganan dua tingkat ccTLD seperti .go.id, .co.id, .ac.id
        if ($count >= 3 && in_array($parts[$count - 1], ['id', 'uk', 'au', 'jp', 'sg'], true)) {
            $secondLevel = $parts[$count - 2];
            if (in_array($secondLevel, ['go', 'co', 'ac', 'or', 'net', 'biz', 'web', 'my'], true)) {
                $domain = $parts[$count - 3] . '.' . $secondLevel . '.' . $parts[$count - 1];
                $tld = $secondLevel . '.' . $parts[$count - 1];
                $subdomain = ($count > 3) ? implode('.', array_slice($parts, 0, $count - 3)) : null;
                return ['domain' => $domain, 'subdomain' => $subdomain, 'tld' => $tld];
            }
        }

        $tld = $parts[$count - 1];
        $domain = $parts[$count - 2] . '.' . $tld;
        $subdomain = ($count > 2) ? implode('.', array_slice($parts, 0, $count - 2)) : null;

        return [
            'domain' => $domain,
            'subdomain' => $subdomain,
            'tld' => $tld
        ];
    }

    /**
     * Mendeteksi pencatutan nama regulator atau institusi resmi (Typosquatting/Impersonation)
     */
    protected function detectTyposquatting(string $host, string $domain): array
    {
        $findings = [];
        $cleanHost = preg_replace('/[^a-z0-9]/', '', $host);

        foreach (self::OFFICIAL_BRANDS as $brandKey => $info) {
            $isLegitimate = false;
            foreach ($info['official_domains'] as $offDomain) {
                if ($domain === $offDomain || str_ends_with($host, '.' . $offDomain)) {
                    $isLegitimate = true;
                    break;
                }
            }

            if ($isLegitimate) {
                continue;
            }

            // Jika host bukan domain resmi namun mengandung nama brand regulator
            $pattern = '/(^|[-_.])' . preg_quote($brandKey, '/') . '([-._\d]|$)/i';
            if (preg_match($pattern, $host) || str_contains($cleanHost, $brandKey)) {
                // Kecualikan false positive jika hanya substring pendek biasa kecuali brand unik
                if (strlen($brandKey) >= 3 || in_array($brandKey, ['bi', 'bri', 'bni', 'bca', 'ojk'], true)) {
                    // Verifikasi apakah benar-benar merepresentasikan brand
                    if (preg_match('/\b' . preg_quote($brandKey, '/') . '\b/i', str_replace(['-', '_', '.'], ' ', $host))) {
                        $findings[] = [
                            'name' => 'typosquatting_detection',
                            'evidence' => "Mencatut '{$brandKey}' pada domain non-resmi '{$host}'",
                            'severity' => 'critical',
                            'explanation' => "Domain menyerupai/mencatut identitas resmi {$info['name']} namun tidak menggunakan domain resmi institusi."
                        ];
                    }
                }
            }
        }

        return $findings;
    }

    /**
     * Mendeteksi pola angka mencurigakan pada URL
     */
    protected function detectSuspiciousNumbers(string $url, string $host): array
    {
        $findings = [];

        // 1. Angka keberuntungan judol (777, 888, 999, 138, 168)
        if (preg_match('/(777|888|999|138|168|88|99)/', $host, $m)) {
            $findings[] = [
                'name' => 'lucky_gambling_numbers',
                'evidence' => $m[0],
                'severity' => 'high',
                'explanation' => "Domain menyematkan angka hoki/keberuntungan khas jaringan perjudian ('{$m[0]}')."
            ];
        }

        // 2. Tahun untuk memberi kesan kekinian (2024, 2025, 2026, 2027)
        if (preg_match('/(202[4-9]|2030)/', $host, $m)) {
            $findings[] = [
                'name' => 'recency_deception_year',
                'evidence' => $m[0],
                'severity' => 'medium',
                'explanation' => "Menyematkan tahun ('{$m[0]}') pada nama domain untuk memberikan kesan penawaran atau izin terbaru."
            ];
        }

        // 3. Deretan digit acak panjang (>= 4 angka berturut-turut pada domain)
        if (preg_match('/\b\d{4,}\b|[-_]\d{4,}/', $host, $m)) {
            $digits = ltrim($m[0], '-_');
            // Jika bukan sekadar tahun yang sudah ditandai
            if (!preg_match('/^202[4-9]$/', $digits)) {
                $findings[] = [
                    'name' => 'random_digits_domain',
                    'evidence' => $digits,
                    'severity' => 'medium',
                    'explanation' => "Domain menggunakan deretan angka acak ('{$digits}'), indikator domain temporer atau kloning massal."
                ];
            }
        }

        return $findings;
    }

    /**
     * Mendeteksi parameter query atau path yang mengarah pada pendaftaran, deposit, atau kode afiliasi
     */
    protected function detectSuspiciousParams(array $queryParams, string $path): array
    {
        $findings = [];

        // Parameter referral / agen sindikat
        $affiliateKeys = ['ref', 'aff', 'affiliate', 'agent', 'referral', 'inviter', 'kode_agen', 'sponsor'];
        foreach ($affiliateKeys as $key) {
            if (isset($queryParams[$key])) {
                $val = is_scalar($queryParams[$key]) ? (string)$queryParams[$key] : '';
                $findings[] = [
                    'name' => 'referral_agent_param',
                    'evidence' => "{$key}={$val}",
                    'severity' => 'high',
                    'explanation' => "Ditemukan parameter kode referral/agen sindikat ('{$key}'), lazim digunakan pada perekrutan member judol atau skema piramida."
                ];
                break;
            }
        }

        // Parameter transaksi deposit / klaim promo
        $promoKeys = ['deposit', 'depo', 'claim', 'bonus', 'freechip', 'slot'];
        foreach ($promoKeys as $key) {
            if (isset($queryParams[$key]) || str_contains(strtolower($path), $key)) {
                $findings[] = [
                    'name' => 'deposit_promo_path',
                    'evidence' => $key,
                    'severity' => 'high',
                    'explanation' => "Parameter atau jalur URL mengarahkan ke sistem deposit/klaim bonus taruhan ('{$key}')."
                ];
                break;
            }
        }

        // Parameter atau path penawaran investasi menyesatkan / janji profit tidak wajar (Spesifikasi Bagian 3.3)
        $investLureKeys = [
            'auto-profit', 'autoprofit', 'passive-income', 'passiveincome', 'cuan-harian',
            'profit-pasti', 'profitpasti', 'balik-modal', 'tanpa-risiko', 'penggandaan-uang'
        ];
        foreach ($investLureKeys as $key) {
            if (isset($queryParams[$key]) || str_contains(strtolower($path), $key)) {
                $findings[] = [
                    'name' => 'misleading_investment_path',
                    'evidence' => $key,
                    'severity' => 'high',
                    'explanation' => "Jalur URL atau parameter memuat klaim penawaran investasi menyesatkan/keuntungan instan ('{$key}')."
                ];
                break;
            }
        }

        return $findings;
    }

    /**
     * Sistem Pencocokan Kata Kunci Judi Online Berjenjang 3 Tingkat (3-Tier Matching):
     * - Tingkat Kuat (Strong): slot gacor, situs togel, casino online -> Indikator kuat
     * - Tingkat Menengah (Medium): maxwin, jackpot, scatter, free spin -> Perlu indikator pendukung
     * - Tingkat Kontekstual (Contextual): zeus, dewa, thor, dragon -> Perlu pemeriksaan tambahan
     * Mencegah false-positive pada website umum/edukasi/berita biasa.
     */
    protected function detectJudolKeywordTiers(string $normalizedUrl): array
    {
        $findings = [];
        $lower = strtolower($normalizedUrl);

        // 1. Tingkat KUAT (Indikator Kuat Eksplisit)
        $strongPattern = '/(judol\d*|slot[-_]?gacor|situs[-_]?togel|casino[-_]?online|kasino[-_]?online|judi[-_]?slot|judi[-_]?online|bandar[-_]?togel|agen[-_]?slot|judi[-_]?bola|sbobet|togel4d|slot88|slot777|gacor777|gacor88|rtp[-_]?slot|depo[-_]?slot|deposit[-_]?slot|maxwin\d*)/i';
        $hasStrong = false;
        if (preg_match($strongPattern, $lower, $mStrong)) {
            $hasStrong = true;
            $findings[] = [
                'name' => 'judol_keyword_strong',
                'tier' => 'kuat',
                'evidence' => $mStrong[0],
                'severity' => 'critical',
                'explanation' => "Pencocokan Tingkat KUAT: Ditemukan frasa eksplisit perjudian online ('{$mStrong[0]}') yang merupakan indikator kuat."
            ];
        }

        // 2. Tingkat MENENGAH (Perlu Indikator Pendukung)
        $mediumPattern = '/(maxwin|jackpot|scatter|free[-_]?spin|rtp[-_]?live|rtp[-_]?tinggi|pragmatic|pgsoft|habanero|pola[-_]?gacor|baccarat|roulette|betting|(?<![a-z])(slot|gacor|togel|casino|kasino)(?![a-z]))/i';
        if (!$hasStrong && preg_match($mediumPattern, $lower, $mMed)) {
            $findings[] = [
                'name' => 'judol_keyword_medium',
                'tier' => 'menengah',
                'evidence' => $mMed[0],
                'severity' => 'medium',
                'explanation' => "Pencocokan Tingkat MENENGAH: Ditemukan istilah taruhan/permainan ('{$mMed[0]}'). Memerlukan indikator pendukung agar tidak terjadi kesalahan deteksi."
            ];
        }

        // 3. Tingkat KONTEKSTUAL (Perlu Pemeriksaan Tambahan)
        $contextualPattern = '/(?<![a-z])(zeus|dewa|thor|dragon|olympus|mahjong|bonanza)(?![a-z])/i';
        if (preg_match($contextualPattern, $lower, $mCtx)) {
            $findings[] = [
                'name' => 'judol_keyword_contextual',
                'tier' => 'kontekstual',
                'evidence' => $mCtx[0],
                'severity' => 'low',
                'explanation' => "Pencocokan Tingkat KONTEKSTUAL: Ditemukan kata kontekstual ('{$mCtx[0]}'). Kata ini memerlukan pemeriksaan tambahan dan bukti pendukung agar situs biasa tidak salah terdeteksi sebagai Judi Online."
            ];
        }

        return $findings;
    }

    /**
     * Penjaga SSRF (Server-Side Request Forgery)
     */
    protected function enforceSsrfGuard(string $host): void
    {
        $blockedHosts = [
            'localhost', '127.0.0.1', '0.0.0.0', '::1',
            '169.254.169.254', 'metadata.google.internal', 'instance-data'
        ];

        if (in_array($host, $blockedHosts, true)) {
            throw new InvalidArgumentException('Akses ke alamat host lokal/internal dilarang demi keamanan.');
        }

        // Cegah resolved private IP jika host berupa nama domain internal
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new InvalidArgumentException('Akses ke rentang IP privat atau reserved dilarang.');
            }
        }
    }
}

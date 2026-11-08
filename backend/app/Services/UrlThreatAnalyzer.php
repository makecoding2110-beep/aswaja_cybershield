<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service Analisis Ancaman Tautan Tingkat Lanjut

 * 1. Web Defacement / Subdomain Hijacking (terutama pada domain .go.id, .ac.id, .desa.id)
 * 2. Open Redirect (ekstraksi URL sekunder dan parameter pengalihan)
 * 3. Typosquatting / URL Spoofing (kemiripan nama instansi resmi & homoglif)
 */
class UrlThreatAnalyzer
{
    protected array $keywords;
    protected array $tlds;
    protected array $officialDomains;
    protected array $weights;
    protected bool $rdapEnabled;
    protected int $rdapTimeout;

    public function __construct()
    {
        $this->keywords = config('threat.keywords') ?: (file_exists(config_path('threat/keywords.php')) ? require config_path('threat/keywords.php') : []);
        $this->tlds = config('threat.tlds') ?: (file_exists(config_path('threat/tlds.php')) ? require config_path('threat/tlds.php') : []);
        $this->officialDomains = config('threat.official_domains') ?: (file_exists(config_path('threat/official_domains.php')) ? require config_path('threat/official_domains.php') : []);
        $this->weights = config('threat.weights') ?: (file_exists(config_path('threat/weights.php')) ? require config_path('threat/weights.php') : []);

        $this->rdapEnabled = filter_var(env('THREAT_RDAP_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
        $this->rdapTimeout = (int) env('THREAT_RDAP_TIMEOUT', 2);
    }

    /**
     * Menganalisis URL terhadap indikator 3 teknik ancaman siber
     *
     * @param string $rawUrl URL target yang ingin dianalisis
     * @return array{
     *     indicators: array,
     *     techniques: array,
     *     threat_score: int,
     *     has_threat: bool,
     *     normalized: array
     * }
     */
    public function analyze(string $rawUrl): array
    {
        $normalized = $this->normalizeUrl($rawUrl);

        $indicators = [];
        $detectedTechniques = [];

        // 1. Deteksi Teknik 1: Web Defacement / Subdomain Hijacking
        $defacementResult = $this->detectWebDefacement($normalized);
        if (!empty($defacementResult['indicators'])) {
            $indicators = array_merge($indicators, $defacementResult['indicators']);
            $detectedTechniques[] = 'web_defacement';
        }

        // 2. Deteksi Teknik 2: Open Redirect
        $openRedirectResult = $this->detectOpenRedirect($normalized);
        if (!empty($openRedirectResult['indicators'])) {
            $indicators = array_merge($indicators, $openRedirectResult['indicators']);
            $detectedTechniques[] = 'open_redirect';
        }

        // 3. Deteksi Teknik 3: Typosquatting / URL Spoofing
        $typosquattingResult = $this->detectTyposquatting($normalized);
        if (!empty($typosquattingResult['indicators'])) {
            $indicators = array_merge($indicators, $typosquattingResult['indicators']);
            $detectedTechniques[] = 'typosquatting';
        }

        // 3.B Deteksi Penyusupan Terselubung (Cloaking, Malvertising Google Ads, & Inspeksi Konten Halaman Live)
        $learnedIntel = $this->checkLearnedDatabaseIntelligence($normalized);
        if (!empty($learnedIntel['indicators'])) {
            $indicators = array_merge($indicators, $learnedIntel['indicators']);
            foreach ($learnedIntel['techniques'] ?? ['web_defacement'] as $tech) {
                $detectedTechniques[] = $tech;
            }
        }

        $cloakedResult = $this->detectCloakingAndLiveJudol($normalized, $learnedIntel['learned_keywords'] ?? []);
        if (!empty($cloakedResult['indicators'])) {
            $indicators = array_merge($indicators, $cloakedResult['indicators']);
            foreach ($cloakedResult['techniques'] ?? ['web_defacement'] as $tech) {
                $detectedTechniques[] = $tech;
            }
        }

        // 4. Deteksi Tambahan (Opsional): Cek Umur Domain via RDAP
        if ($this->rdapEnabled && !empty($normalized['host'])) {
            $rdapIndicator = $this->checkDomainAgeRdap($normalized['host']);
            if ($rdapIndicator) {
                $indicators[] = $rdapIndicator;
            }
        }

        // 5. Hitung Skor Ancaman (Threat Score 0 - 100) dan Aturan Penguatan Kombinasi
        $threatScore = $this->calculateThreatScore($indicators, $normalized, $detectedTechniques);

        return [
            'indicators' => $indicators,
            'techniques' => array_values(array_unique($detectedTechniques)),
            'threat_score' => $threatScore,
            'has_threat' => !empty($detectedTechniques) || $threatScore >= 50,
            'normalized' => $normalized
        ];
    }

    /**
     * Normalisasi URL: parsing komponen, lowercase host, decode bertingkat, punycode check
     */
    public function normalizeUrl(string $url): array
    {
        $url = trim($url);
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        // Deteksi encoding berlapis (maks 3 putaran decode)
        $rounds = 0;
        $current = $url;
        $hasMultiEncoding = false;

        while ($rounds < 3) {
            $decoded = rawurldecode($current);
            if ($decoded === $current) {
                break;
            }
            $rounds++;
            $current = $decoded;
            if ($rounds >= 2) {
                $hasMultiEncoding = true;
            }
        }

        $parsed = parse_url($url) ?: [];
        $host = strtolower($parsed['host'] ?? '');
        $path = $parsed['path'] ?? '/';
        $query = $parsed['query'] ?? '';
        $scheme = strtolower($parsed['scheme'] ?? 'https');

        // Deteksi Punycode / IDN Homograph
        $isPunycode = str_contains($host, 'xn--');

        // Ekstraksi subdomain dan root domain
        $hostParts = explode('.', $host);
        $subdomain = '';
        $rootDomain = $host;

        // Parsing ekstensi ganda (.go.id, .ac.id, dsb.)
        $protectedTlds = $this->tlds['protected_gov_edu'] ?? ['go.id', 'ac.id', 'sch.id', 'mil.id', 'desa.id'];
        $matchedGovTld = null;
        foreach ($protectedTlds as $govTld) {
            if (str_ends_with($host, '.' . $govTld)) {
                $matchedGovTld = $govTld;
                break;
            }
        }

        if ($matchedGovTld) {
            // misal: pemda.kaltimprov.go.id -> tld = go.id, main = kaltimprov, sub = pemda
            $withoutTld = substr($host, 0, -strlen('.' . $matchedGovTld));
            $parts = explode('.', $withoutTld);
            if (count($parts) > 1) {
                $rootDomain = array_pop($parts) . '.' . $matchedGovTld;
                $subdomain = implode('.', $parts);
            } else {
                $rootDomain = $host;
                $subdomain = '';
            }
        } elseif (count($hostParts) > 2) {
            $rootDomain = implode('.', array_slice($hostParts, -2));
            $subdomain = implode('.', array_slice($hostParts, 0, -2));
        }

        return [
            'original_url' => $url,
            'fully_decoded' => $current,
            'scheme' => $scheme,
            'host' => $host,
            'subdomain' => $subdomain,
            'root_domain' => $rootDomain,
            'path' => $path,
            'query' => $query,
            'is_punycode' => $isPunycode,
            'has_multi_encoding' => $hasMultiEncoding,
            'matched_gov_tld' => $matchedGovTld
        ];
    }

    /**
     * Teknik 1: Deteksi Web Defacement & Subdomain Hijacking
     */
    protected function detectWebDefacement(array $norm): array
    {
        $indicators = [];
        $host = $norm['host'];
        $path = strtolower($norm['path']);
        $subdomain = strtolower($norm['subdomain']);
        $matchedGovTld = $norm['matched_gov_tld'];

        $strongKeywords = $this->keywords['strong_judol'] ?? [];
        $contextualKeywords = $this->keywords['contextual_judol'] ?? [];
        $suspiciousNumbers = $this->keywords['suspicious_numbers'] ?? [];
        $uploadPaths = $this->keywords['vulnerable_upload_paths'] ?? [];

        // Aturan: Hanya berlaku jika domain berakhiran institusi pemerintah/pendidikan (.go.id, .ac.id, .desa.id, dll)
        if ($matchedGovTld !== null) {
            // 1. Cek kata kunci judol pada path URL
            $foundJudolPath = [];
            foreach ($strongKeywords as $kw) {
                if (preg_match('/(?:\/|-|_)' . preg_quote($kw, '/') . '(?:\/|-|_|\.|$)/i', $path)) {
                    $foundJudolPath[] = $kw;
                }
            }

            // 2. Cek apakah berada di folder upload rentan (CMS Defacement)
            $inUploadPath = false;
            $matchedUploadFolder = '';
            foreach ($uploadPaths as $up) {
                if (str_contains($path, $up)) {
                    $inUploadPath = true;
                    $matchedUploadFolder = $up;
                    break;
                }
            }

            if (!empty($foundJudolPath)) {
                $kwList = implode(', ', array_unique($foundJudolPath));
                $explanation = $inUploadPath
                    ? "Tautan memanfaatkan folder unggahan berkas ({$matchedUploadFolder}) pada portal resmi .{$matchedGovTld} untuk menyusupkan halaman judi online (Web Defacement)."
                    : "Jalur URL pada domain resmi pemerintah/pendidikan (.{$matchedGovTld}) terindikasi disusupi konten perjudian daring (Web Defacement).";

                $indicators[] = [
                    'name' => 'defacement_path',
                    'technique' => 'web_defacement',
                    'evidence' => "Path: {$path} (Kata kunci: {$kwList})",
                    'severity' => 'critical',
                    'explanation' => $explanation
                ];
            }

            // 3. Cek Subdomain Asing / Hijacked Subdomain (misal: slot88.instansi.go.id, gacor777.desa.id)
            if ($subdomain !== '') {
                $subdomainHasJudol = false;
                $matchedSubKw = [];

                foreach (array_merge($strongKeywords, $contextualKeywords) as $kw) {
                    if (str_contains($subdomain, $kw)) {
                        $subdomainHasJudol = true;
                        $matchedSubKw[] = $kw;
                    }
                }

                $hasNumberPattern = false;
                foreach ($suspiciousNumbers as $num) {
                    if (str_contains($subdomain, $num)) {
                        $hasNumberPattern = true;
                        $matchedSubKw[] = $num;
                    }
                }

                if ($subdomainHasJudol || $hasNumberPattern) {
                    $kwList = implode(', ', array_unique($matchedSubKw));
                    $indicators[] = [
                        'name' => 'subdomain_asing',
                        'technique' => 'web_defacement',
                        'evidence' => "Subdomain: {$subdomain}.{$norm['root_domain']} (Pola: {$kwList})",
                        'severity' => 'high',
                        'explanation' => "Subdomain tidak lazim terdeteksi pada portal resmi .{$matchedGovTld}, mengindikasikan pengambilalihan subdomain (Subdomain Hijacking / DNS Takeover) oleh sindikat judol."
                    ];
                }
            }
        }

        return ['indicators' => $indicators];
    }

    /**
     * Teknik 2: Deteksi Open Redirect
     */
    protected function detectOpenRedirect(array $norm): array
    {
        $indicators = [];
        $query = $norm['query'];
        $host = $norm['host'];

        // Cek URL-encoding berlapis
        if ($norm['has_multi_encoding']) {
            $indicators[] = [
                'name' => 'url_encoding_berlapis',
                'technique' => 'open_redirect',
                'evidence' => $norm['original_url'],
                'severity' => 'medium',
                'explanation' => "URL menggunakan percent-encoding berlapis (%252F / %253A) untuk menyamarkan alamat tujuan dari sistem filter keamanan."
            ];
        }

        if (empty($query)) {
            return ['indicators' => $indicators];
        }

        parse_str($query, $queryParams);
        $redirectParams = $this->keywords['open_redirect_params'] ?? ['url', 'redirect', 'to', 'link', 'dest'];
        $cheapTlds = $this->tlds['suspicious_cheap_tlds'] ?? [];
        $strongKeywords = $this->keywords['strong_judol'] ?? [];

        foreach ($queryParams as $paramName => $paramValue) {
            $paramNameLower = strtolower((string)$paramName);
            if (!in_array($paramNameLower, $redirectParams, true)) {
                continue;
            }

            $rawDest = is_array($paramValue) ? '' : (string)$paramValue;
            $decodedDest = rawurldecode(rawurldecode($rawDest));

            // Periksa apakah parameter memuat URL absolut
            if (preg_match('#^https?://#i', $decodedDest)) {
                $parsedDest = parse_url($decodedDest) ?: [];
                $destHost = strtolower($parsedDest['host'] ?? '');

                // Tandai parameter open redirect
                $indicators[] = [
                    'name' => 'open_redirect_param',
                    'technique' => 'open_redirect',
                    'evidence' => "?{$paramName}=" . substr($decodedDest, 0, 80),
                    'severity' => 'high',
                    'explanation' => "Ditemukan parameter query '?{$paramName}=' yang mengarahkan peramban secara otomatis ke alamat URL eksternal (Open Redirect)."
                ];

                // Cek apakah host tujuan berbeda dari host asal
                if ($destHost !== '' && $destHost !== $host) {
                    $indicators[] = [
                        'name' => 'dua_host_dalam_url',
                        'technique' => 'open_redirect',
                        'evidence' => "Host Asal: {$host} -> Host Tujuan: {$destHost}",
                        'severity' => 'high',
                        'explanation' => "Struktur URL memuat dua host berbeda. Domain terpercaya digunakan sebagai jembatan pembungkus (*cloaking*) untuk mengelabui korban."
                    ];

                    // Cek apakah host tujuan berekstensi TLD mencurigakan
                    $destParts = explode('.', $destHost);
                    $destTld = end($destParts);
                    if (in_array($destTld, $cheapTlds, true)) {
                        $indicators[] = [
                            'name' => 'open_redirect_cheap_tld',
                            'technique' => 'open_redirect',
                            'evidence' => "Domain Tujuan: {$destHost} (.{$destTld})",
                            'severity' => 'critical',
                            'explanation' => "Target pengalihan URL mengarah ke domain TLD murah/disposable (.{$destTld}) yang lazim digunakan jaringan penipuan daring."
                        ];
                    }

                    // Cek apakah host atau path tujuan memuat kata kunci judol
                    $destFull = strtolower($decodedDest);
                    $destJudolKw = [];
                    foreach ($strongKeywords as $kw) {
                        if (str_contains($destFull, $kw)) {
                            $destJudolKw[] = $kw;
                        }
                    }

                    if (!empty($destJudolKw)) {
                        $kwList = implode(', ', array_unique($destJudolKw));
                        $indicators[] = [
                            'name' => 'open_redirect_judol_dest',
                            'technique' => 'open_redirect',
                            'evidence' => "URL Tujuan memuat kata kunci: {$kwList}",
                            'severity' => 'critical',
                            'explanation' => "Tautan Open Redirect bermuara langsung pada alamat sindikat perjudian daring dengan bukti indikator: {$kwList}."
                        ];
                    }
                }
            }
        }

        return ['indicators' => $indicators];
    }

    /**
     * Teknik 3: Deteksi Typosquatting / URL Spoofing
     */
    protected function detectTyposquatting(array $norm): array
    {
        $indicators = [];
        $host = $norm['host'];
        $rootDomain = $norm['root_domain'];

        $brands = $this->officialDomains['brands'] ?? [];
        $suffixes = $this->officialDomains['deceptive_suffixes'] ?? [];
        $homoglyphMap = $this->officialDomains['homoglyph_map'] ?? [];

        // Jangan pernah menandai domain resmi yang valid
        $isOfficial = false;
        foreach ($brands as $brandInfo) {
            foreach ($brandInfo['official_domains'] as $offDom) {
                if ($host === $offDom || str_ends_with($host, '.' . $offDom)) {
                    $isOfficial = true;
                    break 2;
                }
            }
        }

        if ($isOfficial) {
            return ['indicators' => []];
        }

        // Normalisasi homoglif pada nama host (misal: 0jk -> ojk, bappebt1 -> bappebti)
        $normalizedHost = strtr($host, $homoglyphMap);
        $labelBeforeTld = explode('.', $rootDomain)[0] ?? '';
        $normalizedLabel = strtr($labelBeforeTld, $homoglyphMap);

        foreach ($brands as $brandKey => $brandInfo) {
            $canonical = $brandInfo['canonical_label'] ?? $brandKey;
            $brandName = $brandInfo['name'];

            // 1. Cek pencocokan eksplisit nama instansi pada domain non-go.id / non-resmi
            // Untuk singkatan pendek (<= 3 huruf seperti 'bi' atau 'ojk'), wajib dibatasi pemisah titik/strip atau di awal/akhir label
            $containsBrandName = strlen($canonical) <= 3
                ? (bool) preg_match('/(?:^|[.-])' . preg_quote($canonical, '/') . '(?:[.-]|$)|^' . preg_quote($canonical, '/') . '[-a-z0-9]*/i', $normalizedLabel)
                : str_contains($normalizedHost, $canonical);

            // 2. Cek jarak Levenshtein untuk salah ketik mirip (Typosquatting) - hanya untuk label >= 4 karakter
            $lev = (strlen($canonical) >= 4 && strlen($normalizedLabel) >= 4) ? levenshtein($normalizedLabel, $canonical) : 99;
            $similarPercent = 0.0;
            if (strlen($canonical) >= 4 && strlen($normalizedLabel) >= 4) {
                similar_text($normalizedLabel, $canonical, $similarPercent);
            }

            $isSimilar = ($lev > 0 && $lev <= 2) || ($similarPercent >= 80.0 && $similarPercent < 100.0);

            if ($containsBrandName || $isSimilar) {
                // Cek apakah menggunakan TLD selain .go.id / .mil.id
                $isNonGovTld = !str_ends_with($host, '.go.id') && !str_ends_with($host, '.mil.id');

                // Cek adanya embel-embel deceptive (-resmi, -bantuan, -login, dll)
                $matchedSuffix = '';
                foreach ($suffixes as $sfx) {
                    if (str_contains($host, $sfx)) {
                        $matchedSuffix = $sfx;
                        break;
                    }
                }

                $evidenceDesc = $isSimilar
                    ? "Label domain '{$labelBeforeTld}' sangat mirip nama resmi '{$canonical}' (Kemiripan: " . round($similarPercent) . "%)"
                    : "Domain memuat nama institusi resmi '{$canonical}' pada domain tidak resmi";

                if ($matchedSuffix !== '') {
                    $evidenceDesc .= " dengan embel-embel manipulatif '{$matchedSuffix}'";
                }

                $indicators[] = [
                    'name' => 'typosquatting_mirip_instansi',
                    'technique' => 'typosquatting',
                    'evidence' => "Host: {$host} -> Meniru: {$brandName}",
                    'severity' => 'critical',
                    'explanation' => "Domain terindikasi melakukan peniruan identitas lembaga resmi ({$brandName}) menggunakan teknik Typosquatting / URL Spoofing."
                ];

                if ($isNonGovTld) {
                    $indicators[] = [
                        'name' => 'tld_bukan_go_id',
                        'technique' => 'typosquatting',
                        'evidence' => "Ekstensi: {$host} (Bukan portal .go.id/.mil.id)",
                        'severity' => 'high',
                        'explanation' => "Nama instansi pemerintah Indonesia disematkan pada domain komersial atau bebas verifikasi, tipikal situs phishing dan kloningan sindikat."
                    ];
                }
                break;
            }
        }

        return ['indicators' => $indicators];
    }

    /**
     * Hitung Skor Ancaman (Threat Score) berdasarkan pembobotan dan aturan kombinasi
     */
    protected function calculateThreatScore(array $indicators, array $norm, array $techniques): int
    {
        if (empty($indicators)) {
            return 0;
        }

        $weights = $this->weights['indicator_weights'] ?? [];
        $combinationThresholds = $this->weights['combination_thresholds'] ?? [];

        $totalScore = 0;
        $indNames = array_column($indicators, 'name');

        foreach ($indicators as $ind) {
            $name = $ind['name'] ?? '';
            $scoreContribution = $weights[$name] ?? 25;
            $totalScore += $scoreContribution;
        }

        // Redam nilai akumulasi jika hanya berasal dari beberapa indikator minor
        $score = min(100, $totalScore);

        // Aturan Penguatan Kombinasi (Combination Thresholds)
        // 1. Open redirect dengan tujuan judol atau TLD murah -> Minimal 80 (Berbahaya)
        if (in_array('open_redirect_judol_dest', $indNames, true) || (in_array('open_redirect_param', $indNames, true) && in_array('open_redirect_cheap_tld', $indNames, true))) {
            $score = max($score, $combinationThresholds['open_redirect_with_malicious_dest'] ?? 80);
        }

        // 2. Domain instansi pemerintah (.go.id) + path defacement judol -> Minimal 85 (Berbahaya)
        if (in_array('defacement_path', $indNames, true) && $norm['matched_gov_tld'] !== null) {
            $score = max($score, $combinationThresholds['defacement_gov_with_judol'] ?? 85);
        }

        // 3. Typosquatting instansi resmi pada domain non-go.id -> Minimal 80 (Berbahaya)
        if (in_array('typosquatting_mirip_instansi', $indNames, true) && in_array('tld_bukan_go_id', $indNames, true)) {
            $score = max($score, $combinationThresholds['typosquatting_with_deceptive_suffix'] ?? 80);
        }

        // 4. Modus Kamuflase Finansial (Investasi/Trading berkedok Judol) -> Minimal 85 (Berbahaya)
        if (in_array('kamuflase_investasi_judol', $indNames, true)) {
            $score = max($score, 85);
        }

        return min(100, max(0, $score));
    }

    /**
     * Pemeriksaan Umur Domain via Protokol RDAP Resmi (Opsional, Timeout Singkat)
     * Tidak pernah membuka URL target, hanya meminta metadata registrasi domain.
     */
    protected function checkDomainAgeRdap(string $host): ?array
    {
        try {
            $endpoint = "https://rdap.org/domain/{$host}";
            $response = Http::withoutVerifying()
                ->timeout($this->rdapTimeout)
                ->get($endpoint);

            if ($response->successful()) {
                $data = $response->json();
                $events = $data['events'] ?? [];
                $registrationDate = null;

                foreach ($events as $ev) {
                    if (($ev['eventAction'] ?? '') === 'registration') {
                        $registrationDate = $ev['eventDate'] ?? null;
                        break;
                    }
                }

                if ($registrationDate) {
                    $regTime = strtotime($registrationDate);
                    if ($regTime && (time() - $regTime) < (30 * 86400)) {
                        $daysOld = max(1, round((time() - $regTime) / 86400));
                        return [
                            'name' => 'domain_baru',
                            'technique' => 'domain_reputation',
                            'evidence' => "Umur Domain: {$daysOld} hari (Didaftarkan: " . date('Y-m-d', $regTime) . ")",
                            'severity' => 'medium',
                            'explanation' => "Domain baru berusia {$daysOld} hari (< 30 hari). Domain muda kerap didaftarkan secara massal untuk kampanye phishing dan judol musiman."
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Gagal atau timeout = abaikan tanpa mengganggu aliran utama
            Log::info("[UrlThreatAnalyzer] RDAP check skipped: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Mesin Pembelajaran Mandiri (Self-Learning Threat Intelligence) dari Basis Data:
     * 1. Mempelajari domain & tautan yang pernah dilaporkan masyarakat pada tabel `reports` (maupun mock_database.json).
     * 2. Mempelajari domain berbahaya yang pernah terdeteksi sebagai `terindikasi_judol` / `terindikasi_investasi` pada tabel `link_scans`.
     * 3. Mengekstraksi kata kunci / nama brand judol baru secara dinamis dari laporan & riwayat pemindaian.
     */
    protected function checkLearnedDatabaseIntelligence(array $norm): array
    {
        $indicators = [];
        $techniques = [];
        $learnedKeywords = [];

        $host = strtolower($norm['host'] ?? '');
        $cleanHost = (string) preg_replace('/^www\./i', '', $host);
        $fullUrl = strtolower(rtrim($norm['normalized_url'] ?? $norm['original_url'] ?? '', '/'));

        if ($cleanHost === '' || $cleanHost === 'localhost') {
            return ['indicators' => [], 'techniques' => [], 'learned_keywords' => []];
        }

        // Lewati domain resmi yang masuk daftar putih
        $trustedSafeDomains = [
            'ojk.go.id', 'bi.go.id', 'bappebti.go.id', 'kominfo.go.id', 'komdigi.go.id',
            'unmul.ac.id', 'unukaltim.ac.id', 'ugm.ac.id', 'ui.ac.id', 'itb.ac.id', 'kompas.com', 'detik.com', 'nu.or.id',
            'muhammadiyah.or.id', 'google.com', 'youtube.com', 'wikipedia.org'
        ];
        foreach ($trustedSafeDomains as $td) {
            if ($cleanHost === $td || str_ends_with($cleanHost, '.' . $td)) {
                return ['indicators' => [], 'techniques' => [], 'learned_keywords' => []];
            }
        }

        // A. Belajar dari Basis Data Laporan Masyarakat (Tabel `reports` & MockDataRepository)
        try {
            $reports = [];
            if (class_exists(\App\Repositories\MockDataRepository::class)) {
                $reports = app(\App\Repositories\MockDataRepository::class)->getReports();
            } elseif (class_exists(\App\Models\Report::class)) {
                $reports = \App\Models\Report::orderBy('created_at', 'desc')->limit(200)->get()->toArray();
            }

            $matchedReport = null;
            foreach ($reports as $rep) {
                $repStatus = strtolower((string) ($rep['status'] ?? ''));
                if ($repStatus === 'ditolak' || $repStatus === 'rejected') {
                    continue;
                }

                $repUrlRaw = trim((string) ($rep['url'] ?? ''));
                if ($repUrlRaw === '') {
                    continue;
                }
                if (!preg_match('#^https?://#i', $repUrlRaw)) {
                    $repUrlRaw = 'https://' . $repUrlRaw;
                }

                $repHost = strtolower((string) parse_url($repUrlRaw, PHP_URL_HOST));
                $repCleanHost = (string) preg_replace('/^www\./i', '', $repHost);
                $repFullUrl = strtolower(rtrim($repUrlRaw, '/'));

                // Ekstrak kata kunci brand judol dari URL dan deskripsi laporan untuk pembelajaran dinamis
                $textForLearning = strtolower($repUrlRaw . ' ' . ($rep['description'] ?? '') . ' ' . ($rep['verificationNotes'] ?? $rep['verification_notes'] ?? ''));
                if (preg_match_all('/\b([a-z]{3,12}(?:88|777|99|toto|slot|bet|gacor|hoki|parlay|cuan|jp)[a-z0-9]*)\b/i', $textForLearning, $mKw)) {
                    foreach ($mKw[1] as $dynKw) {
                        $learnedKeywords[] = strtolower($dynKw);
                    }
                }

                if ($matchedReport === null && $repCleanHost !== '' && ($cleanHost === $repCleanHost || $fullUrl === $repFullUrl)) {
                    $matchedReport = $rep;
                }
            }

            if ($matchedReport !== null) {
                $repId = $matchedReport['id'] ?? 'ADUAN';
                $repCat = $matchedReport['category'] ?? 'Tautan Mencurigakan';

                // Tentukan teknik yang tepat berdasarkan kategori aduan dan karakteristik host
                $learnedTech = null;
                if (preg_match('/(investasi|trading|crypto|kripto|dana)/i', $repCat) || preg_match('/(cuan|trading|invest|profit)/i', $host)) {
                    $learnedTech = 'kamuflase_investasi';
                } elseif ($norm['matched_gov_tld'] !== null || preg_match('/(\.go\.id|\.desa\.id|\.ac\.id|\.sch\.id|\.mil\.id|\.edu)/i', $host)) {
                    $learnedTech = 'web_defacement';
                }

                $indicators[] = [
                    'name' => 'learned_from_reports_db',
                    'technique' => $learnedTech,
                    'evidence' => "Tiket Aduan #{$repId} ({$repCat})",
                    'severity' => 'critical',
                    'explanation' => "Pembelajaran Basis Data Aduan: Tautan/domain '{$host}' telah tercatat dalam basis data laporan masyarakat Aswaja CyberShield (Tiket #{$repId}: {$repCat}) sebagai situs berbahaya."
                ];
                if ($learnedTech !== null) {
                    $techniques[] = $learnedTech;
                }
            }
        } catch (\Throwable $e) {
            Log::info('[UrlThreatAnalyzer] Self-learning reports lookup skipped: ' . $e->getMessage());
        }

        // B. Belajar dari Basis Data Riwayat Pemindaian Ancaman (Tabel `link_scans`)
        try {
            $scans = [];
            if (class_exists(\App\Services\ScanHistoryService::class)) {
                $scans = app(\App\Services\ScanHistoryService::class)->getRecentScans(150);
            }

            $matchedScan = null;
            foreach ($scans as $scan) {
                $scanCat = (string) ($scan['final_category'] ?? '');
                $scanScore = (int) ($scan['risk_score'] ?? 0);

                if (!in_array($scanCat, ['terindikasi_judol', 'terindikasi_investasi'], true) && $scanScore < 75) {
                    continue;
                }

                $scanDomain = strtolower((string) ($scan['domain'] ?? ''));
                $scanUrl = strtolower((string) ($scan['normalized_url'] ?? $scan['original_url'] ?? ''));
                $scanHost = $scanDomain !== '' ? $scanDomain : strtolower((string) parse_url($scanUrl, PHP_URL_HOST));
                $scanCleanHost = (string) preg_replace('/^www\./i', '', $scanHost);

                // Pelajari kata kunci dari bukti indikator riwayat pemindaian
                $scanIndicatorsJson = json_encode($scan['indicators'] ?? []);
                if (preg_match_all('/\b([a-z]{3,12}(?:88|777|99|toto|slot|bet|gacor|hoki|parlay|cuan)[a-z0-9]*)\b/i', strtolower((string) $scanIndicatorsJson), $mScanKw)) {
                    foreach ($mScanKw[1] as $dynKw) {
                        $learnedKeywords[] = strtolower($dynKw);
                    }
                }

                if ($matchedScan === null && $scanCleanHost !== '' && $cleanHost === $scanCleanHost) {
                    $matchedScan = $scan;
                }
            }

            if ($matchedScan !== null) {
                $scanId = $matchedScan['id'] ?? 'SCAN';
                $scanScore = (int) ($matchedScan['risk_score'] ?? 85);
                $scanLabel = ($matchedScan['final_category'] ?? '') === 'terindikasi_investasi'
                    ? 'Investasi Menyesatkan'
                    : 'Judi Online / Penyusupan Siber';

                // Tentukan teknik yang dipelajari dari data pemindaian
                $scanTechs = is_array($matchedScan['techniques'] ?? null) ? $matchedScan['techniques'] : [];
                $learnedScanTech = null;
                if (in_array('kamuflase_investasi', $scanTechs, true) || ($matchedScan['final_category'] ?? '') === 'terindikasi_investasi' || preg_match('/(cuan|trading|invest|profit)/i', $host)) {
                    $learnedScanTech = 'kamuflase_investasi';
                } elseif (in_array('web_defacement', $scanTechs, true) && ($norm['matched_gov_tld'] !== null || preg_match('/(\.go\.id|\.desa\.id|\.ac\.id|\.sch\.id|\.mil\.id|\.edu)/i', $host))) {
                    $learnedScanTech = 'web_defacement';
                } elseif (in_array('open_redirect', $scanTechs, true)) {
                    $learnedScanTech = 'open_redirect';
                }

                $indicators[] = [
                    'name' => 'learned_from_scan_history',
                    'technique' => $learnedScanTech,
                    'evidence' => "Memori Audit #{$scanId} ({$scanLabel} - Risiko {$scanScore}%)",
                    'severity' => 'critical',
                    'explanation' => "Pembelajaran Riwayat Ancaman: Domain '{$host}' telah dikenali dan dipelajari secara otomatis dari riwayat pemindaian sebelumnya (#{$scanId}) sebagai situs {$scanLabel}."
                ];
                if ($learnedScanTech !== null) {
                    $techniques[] = $learnedScanTech;
                }
            }
        } catch (\Throwable $e) {
            Log::info('[UrlThreatAnalyzer] Self-learning scan history lookup skipped: ' . $e->getMessage());
        }

        return [
            'indicators' => $indicators,
            'techniques' => array_values(array_unique($techniques)),
            'learned_keywords' => array_values(array_unique($learnedKeywords))
        ];
    }

    /**
     * Deteksi Penyusupan Konten Judol Terselubung (Cloaking, Domain Hijacking,
     * Malvertising Google Ads, dan Inspeksi Konten Halaman Live).
     * Menangani kasus di mana nama domain terlihat normal (misal: rumah sakit, kampus, toko, bengkel mobil)
     * namun saat halaman dibuka menampilkan situs judi online (Slot/Togel/Parlay/Hokibet88).
     */
    protected function detectCloakingAndLiveJudol(array $norm, array $learnedKeywords = []): array
    {
        $indicators = [];
        $techniques = [];

        $host = strtolower($norm['host'] ?? '');
        $path = strtolower($norm['path'] ?? '');
        $query = strtolower($norm['query'] ?? '');
        $matchedGovTld = $norm['matched_gov_tld'];

        $strongKeywords = array_values(array_unique(array_merge(
            $this->keywords['strong_judol'] ?? [],
            $learnedKeywords
        )));
        $builderHosts = $this->keywords['abused_builder_hosts'] ?? [];
        $malvertisingParams = $this->keywords['malvertising_params'] ?? ['gad_source', 'gad_campaignid', 'gclid', 'gbraid'];

        // 1. Cek Kata Kunci Judol pada Path Domain Non-Pemerintah
        if ($matchedGovTld === null && $path !== '' && $path !== '/') {
            $foundPathKw = [];
            foreach ($strongKeywords as $kw) {
                if (preg_match('/(?:\/|-|_)' . preg_quote($kw, '/') . '(?:\/|-|_|\.|$)/i', $path)) {
                    $foundPathKw[] = $kw;
                }
            }

            if (!empty($foundPathKw)) {
                $kwList = implode(', ', array_unique($foundPathKw));
                
                // Periksa apakah domain menyamarkan identitas (investasi/trading/keuangan/cuan) -> kamuflase_investasi
                $isInvestmentDisguise = (bool) preg_match('/(cuan|trading|invest|profit|crypto|kripto|forex|saham|titip-dana|dana|arisan|modal|fintech)/i', $host);
                
                // Periksa apakah domain adalah domain institusi/akademik non-gov (seperti .edu, .ac.*, universitas, sekolah, rumah sakit)
                $isLegitSiteCandidate = (bool) preg_match('/(\.edu|\.org|\.sch\b|university|hospital|rumah-sakit|klinik|yayasan)/i', $host) &&
                                        !preg_match('/(88|777|99|slot|bet|gacor|hoki|parlay|toto|vip|cuan)/i', $host);

                if ($isInvestmentDisguise) {
                    $indicators[] = [
                        'name' => 'kamuflase_investasi_judol',
                        'technique' => 'kamuflase_investasi',
                        'evidence' => "Domain: {$host} + Path: {$path} (Kata kunci: {$kwList})",
                        'severity' => 'critical',
                        'explanation' => "Modus Kamuflase Finansial: Domain menggunakan istilah investasi atau trading ('{$host}') untuk memikat calon korban, namun jalur deposit dan transaksi langsung mengarah ke sistem perjudian online ('{$path}')."
                    ];
                    $techniques[] = 'kamuflase_investasi';
                } elseif ($isLegitSiteCandidate) {
                    $indicators[] = [
                        'name' => 'hijacked_path_judol',
                        'technique' => 'web_defacement',
                        'evidence' => "Path: {$path} (Kata kunci: {$kwList})",
                        'severity' => 'critical',
                        'explanation' => "Jalur halaman ('{$path}') pada domain pihak ketiga ('{$host}') memuat kata kunci eksplisit perjudian daring ('{$kwList}'), mengindikasikan pembajakan domain (Web Defacement)."
                    ];
                    $techniques[] = 'web_defacement';
                } else {
                    // Domain judol biasa / standalone (bukan defacement situs resmi)
                    $indicators[] = [
                        'name' => 'direct_judol_path',
                        'technique' => null,
                        'evidence' => "Path: {$path} (Kata kunci: {$kwList})",
                        'severity' => 'critical',
                        'explanation' => "Jalur halaman ('{$path}') pada domain '{$host}' memuat kata kunci eksplisit perjudian daring ('{$kwList}')."
                    ];
                }
            }
        }

        // 2. Cek Penyalahgunaan Platform Web Gratis / Bio-Link (squarespace.com, mssg.me, heylink.me)
        $matchedBuilder = null;
        foreach ($builderHosts as $bh) {
            if ($host === $bh || str_ends_with($host, '.' . $bh)) {
                $matchedBuilder = $bh;
                break;
            }
        }

        // 3. Cek Parameter Malvertising Google Ads (?gad_source=1, gclid, gad_campaignid, gbraid)
        $foundAdParams = [];
        if ($query !== '') {
            parse_str($query, $qArr);
            foreach ($malvertisingParams as $mp) {
                if (isset($qArr[$mp])) {
                    $foundAdParams[] = $mp;
                }
            }
        }

        if ($matchedBuilder !== null && !empty($foundAdParams)) {
            $adList = implode(', ', $foundAdParams);
            $indicators[] = [
                'name' => 'abused_landing_platform',
                'technique' => 'open_redirect',
                'evidence' => "Host: {$host} + Iklan: ?{$adList}",
                'severity' => 'high',
                'explanation' => "Tautan menggunakan platform pembuat halaman instan ({$matchedBuilder}) yang dipromosikan melalui parameter iklan berbayar ({$adList})—modus umum jembatan promosi situs judi online."
            ];
            $techniques[] = 'open_redirect';
        }

        // 4. Inspeksi Konten Halaman Live secara Aman (Membaca DNS TrustPositif, DoH, <title>, <meta>, popup jebakan, & link keluar)
        $liveFindings = $this->inspectLivePageContent($norm, $learnedKeywords);
        if (!empty($liveFindings['indicators'])) {
            $indicators = array_merge($indicators, $liveFindings['indicators']);
            foreach ($liveFindings['techniques'] as $t) {
                $techniques[] = $t;
            }

            // Jika terbukti halaman live adalah judol dan memakai parameter iklan Google Ads
            if (!empty($foundAdParams)) {
                $adList = implode(', ', $foundAdParams);
                $indicators[] = [
                    'name' => 'malvertising_cloaked_ad',
                    'technique' => 'open_redirect',
                    'evidence' => "Parameter Kampanye Iklan: {$adList}",
                    'severity' => 'critical',
                    'explanation' => "Situs judi online ini menyalahgunakan fitur iklan berbayar (Malvertising: {$adList}) pada domain yang tampak normal untuk mengelabui masyarakat."
                ];
                $techniques[] = 'open_redirect';
            }

            // Jika terbukti halaman live adalah judol dan memakai jalur /amp atau /auto
            if (preg_match('#/(?:amp|auto)/?$#i', $path)) {
                $isDeface = ($matchedGovTld !== null || preg_match('/(\.go\.id|\.desa\.id|\.ac\.id|\.sch\.id|\.mil\.id|\.edu)/i', $host));
                $ampTech = $isDeface ? 'web_defacement' : null;
                $indicators[] = [
                    'name' => 'amp_cloaking_path',
                    'technique' => $ampTech,
                    'evidence' => "Jalur Penyusupan: {$host}{$path}",
                    'severity' => 'high',
                    'explanation' => "Pelaku menyusupkan halaman khusus ('{$path}') pada domain '{$host}' untuk menyembunyikan tampilan judi online dari pemilik asli situs (Cloaked Sub-path)."
                ];
                if ($ampTech !== null) {
                    $techniques[] = $ampTech;
                }
            }
        }

        return [
            'indicators' => $indicators,
            'techniques' => array_values(array_unique($techniques))
        ];
    }

    /**
     * Memeriksa isi HTML halaman secara langsung (Safe Read-Only Fetch)
     * serta mendeteksi pemblokiran DNS Komdigi TrustPositif RI (lamanlabuh.aduankonten.id)
     * dan melakukan resolusi DNS-over-HTTPS (DoH) agar konten judol di balik blokir ISP tetap terbongkar.
     */
    protected function inspectLivePageContent(array $norm, array $learnedKeywords = []): array
    {
        $indicators = [];
        $techniques = [];

        $url = $norm['normalized_url'] ?? $norm['original_url'] ?? '';
        $host = strtolower($norm['host'] ?? '');

        if ($url === '' || $host === '' || $host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) {
            return ['indicators' => [], 'techniques' => []];
        }

        // Lewati domain portal resmi / berita nasional terpercaya yang sudah terverifikasi aman
        $trustedSafeDomains = [
            'ojk.go.id', 'bi.go.id', 'bappebti.go.id', 'kominfo.go.id', 'komdigi.go.id',
            'unmul.ac.id', 'unukaltim.ac.id', 'ugm.ac.id', 'ui.ac.id', 'itb.ac.id', 'kompas.com', 'detik.com', 'nu.or.id',
            'muhammadiyah.or.id', 'google.com', 'youtube.com', 'wikipedia.org'
        ];
        foreach ($trustedSafeDomains as $td) {
            if ($host === $td || str_ends_with($host, '.' . $td)) {
                return ['indicators' => [], 'techniques' => []];
            }
        }

        // Cek resolusi DNS cepat agar domain fiktif pada unit test tidak memicu tunggu jaringan
        $resolvedIp = @gethostbyname($host);
        if ($resolvedIp === $host || !filter_var($resolvedIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return ['indicators' => [], 'techniques' => []];
        }

        // Deteksi Pemblokiran DNS Komdigi TrustPositif RI (lamanlabuh.aduankonten.id / IP Sinkhole)
        $sinkholeHosts = $this->keywords['trustpositif_sinkhole_hosts'] ?? ['lamanlabuh.aduankonten.id', 'aduankonten.id', 'trustpositif.kominfo.go.id'];
        $sinkholeIps = $this->keywords['trustpositif_sinkhole_ips'] ?? ['182.23.79.195', '139.255.196.196', '139.255.196.197', '36.86.63.185'];

        $isTrustPositifBlocked = in_array($resolvedIp, $sinkholeIps, true);
        $sinkholeEvidence = $isTrustPositifBlocked ? "IP Sinkhole: {$resolvedIp}" : '';

        if (!$isTrustPositifBlocked && function_exists('dns_get_record')) {
            $dnsRecords = @dns_get_record($host, DNS_A + DNS_CNAME) ?: [];
            foreach ($dnsRecords as $rec) {
                $recHost = strtolower((string) ($rec['host'] ?? ''));
                $recTarget = strtolower((string) ($rec['target'] ?? ''));
                $recIp = (string) ($rec['ip'] ?? '');

                foreach ($sinkholeHosts as $sh) {
                    if (str_contains($recHost, $sh) || str_contains($recTarget, $sh)) {
                        $isTrustPositifBlocked = true;
                        $sinkholeEvidence = "DNS Sinkhole: " . ($recTarget !== '' ? $recTarget : $recHost) . ($recIp !== '' ? " ({$recIp})" : '');
                        break 2;
                    }
                }
                if (in_array($recIp, $sinkholeIps, true)) {
                    $isTrustPositifBlocked = true;
                    $sinkholeEvidence = "DNS Sinkhole: lamanlabuh.aduankonten.id ({$recIp})";
                    break;
                }
            }
        }

        $dohIp = null;
        if ($isTrustPositifBlocked) {
            $indicators[] = [
                'name' => 'komdigi_trustpositif_block',
                'technique' => null,
                'evidence' => $sinkholeEvidence !== '' ? $sinkholeEvidence : "TrustPositif Sinkhole ({$resolvedIp})",
                'severity' => 'critical',
                'explanation' => "Pemblokiran DNS Pemerintah (TrustPositif Komdigi RI): Domain '{$host}' terdeteksi telah diblokir dan dialihkan ke 'lamanlabuh.aduankonten.id' karena terindikasi memuat konten perjudian online."
            ];

            // Resolusi IP Asli melalui DNS-over-HTTPS (DoH) agar kita tetap bisa mengekstrak bukti judul halaman judol asli seperti di Chrome
            $dohIp = $this->resolveRealIpViaDoh($host, $sinkholeIps);
        }

        try {
            $ch = curl_init($url);
            $curlOpts = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 13; SM-S908B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36'
            ];

            if ($dohIp !== null) {
                $curlOpts[CURLOPT_RESOLVE] = ["{$host}:443:{$dohIp}", "{$host}:80:{$dohIp}"];
            }

            curl_setopt_array($ch, $curlOpts);

            $html = (string) @curl_exec($ch);
            $httpCode = (int) @curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Jika gagal terhubung dengan DNS lokal (misal diblokir ISP tanpa terdeteksi), coba sekali lagi via DoH
            if (($httpCode < 200 || trim($html) === '') && $dohIp === null) {
                $fallbackDohIp = $this->resolveRealIpViaDoh($host, $sinkholeIps);
                if ($fallbackDohIp !== null && $fallbackDohIp !== $resolvedIp) {
                    $ch2 = curl_init($url);
                    $curlOpts[CURLOPT_RESOLVE] = ["{$host}:443:{$fallbackDohIp}", "{$host}:80:{$fallbackDohIp}"];
                    curl_setopt_array($ch2, $curlOpts);
                    $html = (string) @curl_exec($ch2);
                    $httpCode = (int) @curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                }
            }

            if ($httpCode < 200 || $httpCode >= 400 || trim($html) === '') {
                return [
                    'indicators' => $indicators,
                    'techniques' => array_values(array_unique($techniques))
                ];
            }

            // Batasi ukuran analisis teks maksimal 150 KB demi kecepatan
            $htmlSlice = substr($html, 0, 153600);

            $strongKeywords = array_values(array_unique(array_merge(
                $this->keywords['strong_judol'] ?? [],
                $learnedKeywords
            )));
            $livePhrases = $this->keywords['live_content_judol_phrases'] ?? [];

            // A. Ekstrak <title> dan <meta description / og:title>
            $pageTitle = '';
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $htmlSlice, $mTitle)) {
                $pageTitle = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($mTitle[1]))));
            }

            $metaText = '';
            if (preg_match_all('/<meta[^>]+(?:name|property)=["\'](?:description|og:title|og:description|keywords)["\'][^>]+content=["\']([^"\']+)["\']/i', $htmlSlice, $mMeta)) {
                $metaText = implode(' | ', $mMeta[1]);
            }

            $headerCombined = strtolower($pageTitle . ' ' . $metaText);
            $matchedHeaderKeywords = [];

            foreach (array_merge($strongKeywords, $livePhrases) as $kw) {
                if ($kw !== '' && preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $headerCombined)) {
                    $matchedHeaderKeywords[] = $kw;
                } elseif (strlen($kw) >= 5 && str_contains($headerCombined, strtolower($kw))) {
                    $matchedHeaderKeywords[] = $kw;
                }
            }

            if (!empty($matchedHeaderKeywords)) {
                $kwList = implode(', ', array_slice(array_unique($matchedHeaderKeywords), 0, 5));
                $displayTitle = $pageTitle !== '' ? substr($pageTitle, 0, 110) : substr($metaText, 0, 110);
                
                $titleTech = null;
                if (preg_match('/(cuan|trading|invest|profit|crypto|kripto|forex|saham|titip-dana|dana|arisan)/i', $host)) {
                    $titleTech = 'kamuflase_investasi';
                } elseif ($norm['matched_gov_tld'] !== null || preg_match('/(\.go\.id|\.desa\.id|\.ac\.id|\.sch\.id|\.mil\.id|\.edu)/i', $host)) {
                    $titleTech = 'web_defacement';
                }

                $indicators[] = [
                    'name' => 'cloaked_live_judol_title',
                    'technique' => $titleTech,
                    'evidence' => "Judul Halaman: \"{$displayTitle}\" (Indikator: {$kwList})",
                    'severity' => 'critical',
                    'explanation' => "Meskipun nama alamat domain ('{$host}') berkedok umum/finansial, saat halaman dibuka ditemukan judul dan identitas situs judi online aktif: \"{$displayTitle}\"."
                ];
                if ($titleTech !== null) {
                    $techniques[] = $titleTech;
                }
            }

            // B. Deteksi Popup Overlay Penyusupan (#popup-situs) & Tautan Keluar Bandar Judol
            $hasSuspiciousPopup = str_contains($htmlSlice, '#popup-situs') || str_contains($htmlSlice, 'popup-situs');
            $outboundJudolLinks = [];

            if (preg_match_all('/(?:href|src)=["\'](https?:\/\/[^"\']+)["\']/i', $htmlSlice, $mLinks)) {
                foreach (array_unique($mLinks[1]) as $extLink) {
                    $extLinkLower = strtolower($extLink);
                    $extHost = strtolower(parse_url($extLink, PHP_URL_HOST) ?? '');
                    if ($extHost === '' || $extHost === $host) {
                        continue;
                    }
                    foreach (array_merge($strongKeywords, ['araitoto', 'kpktoto', 'adskpk', 'startogel', 'mauslot', 'hokibet88', 'referral_code=googleads']) as $kw) {
                        if (str_contains($extLinkLower, $kw)) {
                            $outboundJudolLinks[] = $extLink;
                            break;
                        }
                    }
                }
            }

            if ($hasSuspiciousPopup || !empty($outboundJudolLinks)) {
                $sampleLink = !empty($outboundJudolLinks) ? substr($outboundJudolLinks[0], 0, 90) : 'Popup Overlay #popup-situs';
                $indicators[] = [
                    'name' => 'cloaked_outbound_judol_link',
                    'technique' => 'open_redirect',
                    'evidence' => "Target Tersembunyi: {$sampleLink}",
                    'severity' => 'critical',
                    'explanation' => "Halaman web menyisipkan layar jebakan (popup overlay) atau tombol pendaftaran yang diam-diam mengarahkan pengunjung ke server bandar judi online ({$sampleLink})."
                ];
                $techniques[] = 'open_redirect';
            }

            // C. Cek Teks Konten Halaman Terlihat (Body Text)
            $visibleText = strtolower(trim(preg_replace('/\s+/', ' ', strip_tags(preg_replace('/<(script|style)[^>]*>.*?<\/\1>/is', ' ', $htmlSlice)))));
            $matchedBodyPhrases = [];
            foreach ($livePhrases as $phrase) {
                if (str_contains($visibleText, strtolower($phrase))) {
                    $matchedBodyPhrases[] = $phrase;
                }
            }

            if (!empty($matchedBodyPhrases)) {
                $phraseList = implode(', ', array_slice(array_unique($matchedBodyPhrases), 0, 6));

                $bodyTech = null;
                if (preg_match('/(cuan|trading|invest|profit|crypto|kripto|forex|saham|titip-dana|dana|arisan)/i', $host)) {
                    $bodyTech = 'kamuflase_investasi';
                } elseif ($norm['matched_gov_tld'] !== null || preg_match('/(\.go\.id|\.desa\.id|\.ac\.id|\.sch\.id|\.mil\.id|\.edu)/i', $host)) {
                    $bodyTech = 'web_defacement';
                }

                $indicators[] = [
                    'name' => 'cloaked_live_judol_content',
                    'technique' => $bodyTech,
                    'evidence' => "Konten Halaman: {$phraseList}",
                    'severity' => 'critical',
                    'explanation' => "Saat isi halaman diperiksa, ditemukan materi promosi taruhan dan judi online secara langsung ({$phraseList}) yang disamarkan di balik domain '{$host}'."
                ];
                if ($bodyTech !== null) {
                    $techniques[] = $bodyTech;
                }
            }
        } catch (\Throwable $e) {
            Log::info("[UrlThreatAnalyzer] Live page inspection skipped for {$host}: " . $e->getMessage());
        }

        return [
            'indicators' => $indicators,
            'techniques' => array_values(array_unique($techniques))
        ];
    }

    /**
     * Resolusi IP Asli Domain melalui DNS-over-HTTPS (Google DoH / Cloudflare DoH)
     * untuk melewati blokir sinkhole DNS lokal ISP ketika mengambil bukti HTML halaman judol.
     */
    protected function resolveRealIpViaDoh(string $host, array $sinkholeIps = []): ?string
    {
        $dohEndpoints = [
            'https://dns.google/resolve?name=' . urlencode($host) . '&type=A',
            'https://cloudflare-dns.com/dns-query?name=' . urlencode($host) . '&type=A',
        ];

        foreach ($dohEndpoints as $endpoint) {
            try {
                $ch = curl_init($endpoint);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 2,
                    CURLOPT_CONNECTTIMEOUT => 1,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => ['Accept: application/dns-json']
                ]);
                $raw = (string) @curl_exec($ch);
                $code = (int) @curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($code === 200 && $raw !== '') {
                    $data = json_decode($raw, true);
                    foreach ($data['Answer'] ?? [] as $ans) {
                        $ip = trim((string) ($ans['data'] ?? ''));
                        if (($ans['type'] ?? 0) === 1 && filter_var($ip, FILTER_VALIDATE_IP) && !in_array($ip, $sinkholeIps, true)) {
                            return $ip;
                        }
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }
}


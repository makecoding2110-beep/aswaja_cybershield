<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Service Integrasi Suricata NIDS (Network Intrusion Detection System)
 * Mengelola ingestion log EVE JSON, ekstraksi event jaringan, dan deteksi signature.
 * Sesuai Dokumen Spesifikasi Bagian 4.1, 4.2, 4.3, dan Aturan No. 8
 */
class SuricataIntegrationService
{
    protected string $eveLogPath;
    protected bool $enabled;

    /**
     * Ruleset Resmi Suricata NIDS Aswaja CyberShield
     */
    protected const DEFAULT_RULES = [
        'pass http any any -> any any (msg:"ASWAJA-IDS: Domain Resmi Pengawas Pemerintah (OJK/Bappebti/Kominfo)"; pcre:"/\\.(ojk|bappebti|bi|kominfo|go)\\.id/i"; classtype:not-suspicious; priority:4; sid:1000000; rev:1;)',
        'alert dns any any -> any any (msg:"SUSPICIOUS DOMAIN - JUDI ONLINE [TINGKAT KUAT]"; dns.query; pcre:"/(slot[-_]?gacor|situs[-_]?togel|casino[-_]?online|judi[-_]?slot|bandar[-_]?togel|slot88|slot777|gacor777)/i"; classtype:policy-violation; priority:1; sid:1000001; rev:2; metadata:fiqih_aswaja maysir_sharih tier_strong;)',
        'alert http any any -> any any (msg:"SUSPICIOUS URL - JUDI ONLINE [TINGKAT MENENGAH: MAXWIN/JACKPOT/SCATTER/FREE SPIN]"; flow:established,to_server; http.uri; pcre:"/(maxwin|jackpot|scatter|free[-_]?spin|rtp[-_]?live|pragmatic|pgsoft)/i"; classtype:policy-violation; priority:2; sid:1000002; rev:2; metadata:fiqih_aswaja maysir_sharih tier_medium;)',
        'alert http any any -> any any (msg:"CONTEXTUAL CHECK - TEMA MITOLOGI [TINGKAT KONTEKSTUAL: ZEUS/DEWA/THOR/DRAGON]"; flow:established,to_server; http.uri; pcre:"/(?<![a-z])(zeus|dewa|thor|dragon|olympus)(?![a-z])/i"; classtype:misc-activity; priority:3; sid:1000003; rev:2; metadata:fiqih_aswaja contextual_check tier_contextual;)',
        'alert tls any any -> any any (msg:"SUSPICIOUS TLS DOMAIN - JUDI ONLINE"; tls.sni; content:"gacor"; nocase; priority:1; sid:1000004; rev:1; metadata:fiqih_aswaja maysir_sharih;)',
        'alert http any any -> any any (msg:"SUSPICIOUS TLD - FAST FLUX JUDOL"; pcre:"/\\.(vip|cc|xyz|top|fun|click|site|live|club|icu|monster)(\\/|$|\\?)/i"; classtype:suspicious-filename; priority:2; sid:1000005; rev:1;)',
        'alert http any any -> any any (msg:"SUSPICIOUS PROMO - INVESTASI MENYESATKAN / ROBOT TRADING BODONG"; pcre:"/(robot-trading|profit-pasti|cuan-harian|balik-modal-cepat|auto-profit)/i"; classtype:bad-unknown; priority:1; sid:1000006; rev:1; metadata:fiqih_aswaja aklu_amwal_bil_bathil;)',
        'alert http any any -> any any (msg:"SUSPICIOUS TRAFFIC - AFILIASI SINDIKAT JUDOL"; pcre:"/(\\?|&)(ref|aff|affiliate|agent|referral)=[a-zA-Z0-9_-]+/i"; classtype:suspicious-traffic; priority:3; sid:1000007; rev:1;)',
        'alert http any any -> any any (msg:"SUSPICIOUS PARAMETER - DEPOSIT JUDOL"; pcre:"/(deposit-(dana|gopay|qris|pulsa)|depo-pulsa|depo-10k)/i"; classtype:suspicious-traffic; priority:1; sid:1000008; rev:1;)'
    ];

    public function __construct(?string $eveLogPath = null, ?bool $enabled = null)
    {
        $this->eveLogPath = $eveLogPath ?? (string) config('services.suricata.eve_log_path', storage_path('logs/suricata/eve.json'));
        $this->enabled = $enabled ?? (bool) config('services.suricata.enabled', true);
    }

    /**
     * Memeriksa apakah sensor jaringan Suricata tersedia (berkas eve.json ada dan dapat dibaca)
     * Kepatuhan Aturan 8: Jika file belum ada, tandai tidak tersedia tanpa membuat data tiruan.
     */
    public function isAvailable(): bool
    {
        if (!$this->enabled) {
            return false;
        }

        if (!file_exists($this->eveLogPath) || !is_file($this->eveLogPath)) {
            return false;
        }

        // Pada Windows, is_readable() dapat terhambat izin ACL PHP CLI; fallback cek fopen
        if (is_readable($this->eveLogPath)) {
            return true;
        }

        $fp = @fopen($this->eveLogPath, 'r');
        if ($fp !== false) {
            fclose($fp);
            return true;
        }

        return false;
    }

    public function getEveLogPath(): string
    {
        return $this->eveLogPath;
    }

    /**
     * Memeriksa riwayat event jaringan dari EVE JSON terkait host atau URL target
     *
     * @param string $host Nama domain atau alamat IP target (misal: slot-gacor777.top)
     * @param string $url URL lengkap target
     * @param int|null $maxLines Batas pembacaan baris log terakhir (default 500)
     * @return array
     */
    public function inspectNetworkEvents(string $host, string $url = '', int $maxLines = 500): array
    {
        if (!$this->isAvailable()) {
            return [
                'status' => 'tidak_tersedia',
                'available' => false,
                'matched' => false,
                'alerts_count' => 0,
                'alerts' => [],
                'dns_events' => [],
                'http_events' => [],
                'tls_events' => [],
                'message' => 'Sensor jaringan Suricata belum aktif atau berkas log eve.json belum tersedia di host.',
                'indicators' => []
            ];
        }

        $cleanHost = strtolower(trim($host));
        $cleanUrl = trim($url);

        $alerts = [];
        $dnsEvents = [];
        $httpEvents = [];
        $tlsEvents = [];
        $indicators = [];

        // Baca file eve.json (baris demi baris dari akhir atau streaming)
        $lines = $this->readRecentLines($this->eveLogPath, $maxLines);

        foreach ($lines as $line) {
            $record = $this->parseEveLine($line);
            if ($record === null) {
                continue;
            }

            $matchesTarget = false;

            // 1. Periksa kecocokan pada event DNS
            if ($record['event_type'] === 'dns' && !empty($record['dns_query']) && str_contains(strtolower($record['dns_query']), $cleanHost)) {
                $dnsEvents[] = $record;
                $matchesTarget = true;
            }

            // 2. Periksa kecocokan pada event TLS SNI
            if ($record['event_type'] === 'tls' && !empty($record['tls_sni']) && str_contains(strtolower($record['tls_sni']), $cleanHost)) {
                $tlsEvents[] = $record;
                $matchesTarget = true;
            }

            // 3. Periksa kecocokan pada event HTTP Host / URI
            if ($record['event_type'] === 'http') {
                if (!empty($record['http_host']) && str_contains(strtolower($record['http_host']), $cleanHost)) {
                    $httpEvents[] = $record;
                    $matchesTarget = true;
                } elseif (!empty($record['http_uri']) && !empty($cleanUrl) && str_contains($cleanUrl, $record['http_uri'])) {
                    $httpEvents[] = $record;
                    $matchesTarget = true;
                }
            }

            // 4. Jika event berupa ALERT dan berhubungan dengan host/target
            if ($record['event_type'] === 'alert' && ($matchesTarget || $this->alertMatchesTarget($record, $cleanHost))) {
                $alerts[] = $record;

                $severityName = match ((int)($record['severity'] ?? 3)) {
                    1 => 'critical',
                    2 => 'high',
                    default => 'medium'
                };

                $indicators[] = [
                    'name' => 'suricata_network_alert',
                    'evidence' => "SID: {$record['signature_id']} - {$record['alert_signature']}",
                    'severity' => $severityName,
                    'explanation' => "Pelanggaran signature Suricata NIDS pada lalu lintas jaringan: {$record['alert_signature']}"
                ];
            }
        }

        $hasAlerts = count($alerts) > 0;

        return [
            'status' => 'tersedia',
            'available' => true,
            'matched' => $hasAlerts,
            'alerts_count' => count($alerts),
            'alerts' => $alerts,
            'dns_events' => $dnsEvents,
            'http_events' => $httpEvents,
            'tls_events' => $tlsEvents,
            'message' => $hasAlerts
                ? "Ditemukan " . count($alerts) . " peringatan signature Suricata pada aktivitas jaringan."
                : 'Lalu lintas jaringan terpantau bersih tanpa alert signature Suricata.',
            'indicators' => $indicators
        ];
    }

    /**
     * Mem-parsing satu baris log EVE JSON menjadi array terstandar
     * Mengambil 11 parameter sesuai Spesifikasi Bagian 4.3
     */
    public function parseEveLine(string $line): ?array
    {
        $trimmed = rtrim(trim($line), ',');
        if ($trimmed === '' || !str_starts_with($trimmed, '{')) {
            return null;
        }

        $json = json_decode($trimmed, true);
        if (!is_array($json)) {
            return null;
        }

        $eventType = $json['event_type'] ?? 'unknown';

        $dnsQuery = $json['dns']['query'][0]['rrname'] ?? ($json['dns']['rrname'] ?? null);
        $httpHost = $json['http']['hostname'] ?? null;
        $httpUri = $json['http']['url'] ?? null;
        $tlsSni = $json['tls']['sni'] ?? null;

        $alertSignature = $json['alert']['signature'] ?? null;
        $signatureId = isset($json['alert']['signature_id']) ? (int)$json['alert']['signature_id'] : null;
        $severity = isset($json['alert']['severity']) ? (int)$json['alert']['severity'] : null;

        return [
            'timestamp' => $json['timestamp'] ?? now()->toIso8601String(),
            'event_type' => $eventType,
            'src_ip' => $json['src_ip'] ?? '0.0.0.0',
            'dest_ip' => $json['dest_ip'] ?? '0.0.0.0',
            'proto' => $json['proto'] ?? 'TCP',
            'dns_query' => $dnsQuery,
            'http_host' => $httpHost,
            'http_uri' => $httpUri,
            'tls_sni' => $tlsSni,
            'alert_signature' => $alertSignature,
            'signature_id' => $signatureId,
            'severity' => $severity,
            'raw_event' => $json
        ];
    }

    /**
     * Memeriksa apakah event alert cocok dengan target berdasarkan host/query
     */
    protected function alertMatchesTarget(array $record, string $cleanHost): bool
    {
        if (!empty($record['dns_query']) && str_contains(strtolower($record['dns_query']), $cleanHost)) {
            return true;
        }
        if (!empty($record['tls_sni']) && str_contains(strtolower($record['tls_sni']), $cleanHost)) {
            return true;
        }
        if (!empty($record['http_host']) && str_contains(strtolower($record['http_host']), $cleanHost)) {
            return true;
        }

        return false;
    }

    /**
     * Membaca N baris/event terakhir dari berkas log secara efisien.
     * Mendukung format JSON Lines (NDJSON native Suricata) maupun JSON Array standar ([...]).
     */
    protected function readRecentLines(string $filePath, int $maxLines): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $rawContent = @file_get_contents($filePath);
        if ($rawContent === false || trim($rawContent) === '') {
            return [];
        }

        $trimmedContent = trim($rawContent);
        // Jika berkas diformat sebagai JSON Array standar ([ ... ]) agar valid di IDE linter
        if (str_starts_with($trimmedContent, '[')) {
            $decodedArray = json_decode($trimmedContent, true);
            if (is_array($decodedArray)) {
                $lines = array_map(fn($item) => json_encode($item), $decodedArray);
                return count($lines) <= $maxLines ? $lines : array_slice($lines, -$maxLines);
            }
        }

        $file = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($file === false) {
            return [];
        }

        if (count($file) <= $maxLines) {
            return $file;
        }

        return array_slice($file, -$maxLines);
    }

    /**
     * Mengambil daftar aturan resmi Suricata (.rules)
     */
    public function getDefaultRules(): array
    {
        return self::DEFAULT_RULES;
    }

    /**
     * Mengekspor aturan resmi Suricata ke berkas .rules untuk deploy sensor di Linux/Firewall
     */
    public function exportRules(string $outputPath): bool
    {
        $content = "# ====================================================================\n";
        $content .= "# ASWAJA CYBERSHIELD - SURICATA NIDS RULES (JUDOL & INVESTASI BODONG)\n";
        $content .= "# Generated At: " . now()->toIso8601String() . "\n";
        $content .= "# ====================================================================\n\n";

        foreach (self::DEFAULT_RULES as $rule) {
            $content .= $rule . "\n";
        }

        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        return file_put_contents($outputPath, $content) !== false;
    }
}

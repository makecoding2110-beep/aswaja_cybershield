<?php

namespace App\Services;

use App\DTO\AnalysisResultDTO;

/**
 * Suricata IDS Rule Engine Service
 * Mengimplementasikan Signature-Based Intrusion Detection System (IDS) Suricata
 * untuk deteksi awal (Layer 1) tautan perjudian online (judol) dan investasi bodong.
 */
class SuricataService
{
    protected array $rules = [
        [
            'sid' => 1000000,
            'rev' => 1,
            'action' => 'PASS',
            'priority' => 4,
            'classtype' => 'not-suspicious',
            'msg' => 'Domain Resmi Pengawas Pemerintah (OJK/Bappebti/Kominfo)',
            'pcre' => '/\.(ojk|bappebti|bi|kominfo|go)\.id/i',
            'metadata' => 'whitelist'
        ],
        [
            'sid' => 1000001,
            'rev' => 2,
            'action' => 'ALERT',
            'priority' => 1,
            'tier' => 'kuat',
            'classtype' => 'policy-violation',
            'msg' => 'Tingkat Kuat: Terindikasi Eksplisit Judi Online (Slot Gacor / Situs Togel / Casino Online)',
            'pcre' => '/(slot[-_]?gacor|situs[-_]?togel|casino[-_]?online|kasino[-_]?online|judi[-_]?slot|judi[-_]?online|bandar[-_]?togel|agen[-_]?slot|judi[-_]?bola|sbobet|togel4d|slot88|slot777|gacor777|gacor88|deposit[-_]?slot|depo[-_]?slot|singapore4d|hongkongpools|sydney4d)/i',
            'metadata' => 'fiqih_aswaja maysir_sharih tier_strong'
        ],
        [
            'sid' => 1000002,
            'rev' => 2,
            'action' => 'ALERT',
            'priority' => 2,
            'tier' => 'menengah',
            'classtype' => 'policy-violation',
            'msg' => 'Tingkat Menengah: Istilah Taruhan / Fitur Slot (Maxwin / Jackpot / Scatter / Free Spin)',
            'pcre' => '/(maxwin|jackpot|scatter|free[-_]?spin|rtp[-_]?live|rtp[-_]?tinggi|pragmatic|pgsoft|habanero|pola[-_]?gacor|baccarat|roulette|sicbo|colok[-_]?bebas|(?<![a-z])(slot|gacor|togel|casino|kasino)(?![a-z]))/i',
            'metadata' => 'fiqih_aswaja maysir_sharih tier_medium'
        ],
        [
            'sid' => 1000003,
            'rev' => 2,
            'action' => 'ALERT',
            'priority' => 3,
            'tier' => 'kontekstual',
            'classtype' => 'misc-activity',
            'msg' => 'Tingkat Kontekstual: Nama Tema / Mitologi (Zeus / Dewa / Thor / Dragon) - Perlu Pemeriksaan Tambahan',
            'pcre' => '/(?<![a-z])(zeus|dewa|thor|dragon|olympus|mahjong|bonanza)(?![a-z])/i',
            'metadata' => 'fiqih_aswaja contextual_check tier_contextual'
        ],
        [
            'sid' => 1000004,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 1,
            'classtype' => 'suspicious-traffic',
            'msg' => 'Parameter Deposit Cepat / Rekening Penampung Judol',
            'pcre' => '/(deposit-(dana|gopay|qris|pulsa)|depo-pulsa|depo-10k|bonus-new-member-100)/i',
            'metadata' => 'fiqih_aswaja gharar_fasid'
        ],
        [
            'sid' => 1000005,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 2,
            'classtype' => 'suspicious-filename',
            'msg' => 'TLD Reputasi Buruk Kerap Dipakai Sindikat Judol',
            'pcre' => '/\.(vip|cc|xyz|top|fun|click|site|live|club|icu|monster)(\/|$|\?)/i',
            'metadata' => 'fiqih_aswaja syubhat_dom'
        ],
        [
            'sid' => 1000006,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 1,
            'classtype' => 'bad-unknown',
            'msg' => 'Janji Keuntungan Pasif / Robot Trading Tak Berizin',
            'pcre' => '/(robot-trading|profit-pasti|cuan-harian|arisan-online-syariah|investasi-titip-dana)/i',
            'metadata' => 'fiqih_aswaja aklu_amwal_bil_bathil'
        ],
        [
            'sid' => 1000007,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 1,
            'classtype' => 'trojan-activity',
            'msg' => 'Upaya Pengunduhan APK Liar / Malware Siber',
            'pcre' => '/\.(apk|exe|scr|bat|vbs)(\?|$)/i',
            'metadata' => 'fiqih_aswaja dharar_amwal'
        ],
        [
            'sid' => 1000008,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 3,
            'classtype' => 'suspicious-traffic',
            'msg' => 'Tautan Referal / Afiliasi Perekrutan Pemain Judi',
            'pcre' => '/(\?|&)(ref|aff|affiliate|agent|referral)=[a-zA-Z0-9_-]+/i',
            'metadata' => 'fiqih_aswaja taawun_alal_itsmi'
        ],
        [
            'sid' => 1000101,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 1,
            'classtype' => 'policy-violation',
            'msg' => 'ASWAJA-IDS Kata kunci judol pada URI',
            'pcre' => '/(slot|gacor|maxwin|togel|sbobet|scatter|rtp)/i',
            'metadata' => 'fiqih_aswaja maysir_sharih'
        ],
        [
            'sid' => 1000102,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 1,
            'classtype' => 'policy-violation',
            'msg' => 'ASWAJA-IDS Parameter open redirect ke URL eksternal',
            'pcre' => '/[?&](url|redirect|to|link|dest|next|goto|return)=(https?(%3A|:)(%2F|\/){2})/i',
            'metadata' => 'technique open_redirect'
        ],
        [
            'sid' => 1000103,
            'rev' => 1,
            'action' => 'ALERT',
            'priority' => 1,
            'classtype' => 'policy-violation',
            'msg' => 'ASWAJA-IDS Query DNS domain berkata kunci judol',
            'pcre' => '/(slot|gacor|maxwin|togel)/i',
            'metadata' => 'technique keyword_judol'
        ]
    ];

    /**
     * Inspeksi URL menggunakan Signature Rules Suricata
     */
    public function inspect(string $url): array
    {
        $target = trim($url);
        $alerts = [];
        $isPassedByWhitelist = false;

        $hasStrongJudol = false;
        foreach ($this->rules as $rule) {
            if (preg_match($rule['pcre'], $target)) {
                if ($rule['action'] === 'PASS') {
                    $isPassedByWhitelist = true;
                    $alerts[] = $rule;
                    break;
                }
                if (($rule['tier'] ?? '') === 'kuat') {
                    $hasStrongJudol = true;
                }
                if (($rule['tier'] ?? '') === 'menengah' && $hasStrongJudol) {
                    // Jangan duplikasi rule menengah jika rule kuat sudah cocok
                    continue;
                }
                $alerts[] = $rule;
            }
        }

        if ($isPassedByWhitelist) {
            return [
                'matched' => false,
                'isWhitelist' => true,
                'verdict' => 'PASS',
                'score' => 98,
                'category' => 'belum_ditemukan_indikasi',
                'statusAlert' => 'Status Aman Terverifikasi (Suricata PASS)',
                'alertClass' => 'emerald',
                'title' => 'Aman: Terverifikasi oleh Suricata IDS Whitelist',
                'alerts' => $alerts
            ];
        }

        // Jika HANYA indikator tingkat Kontekstual (zeus/dewa/thor/dragon) yang cocok tanpa indikator lain,
        // jangan anggap sebagai pelanggaran Suricata agar website umum tidak salah terdeteksi (false positive).
        $nonContextualAlerts = array_filter($alerts, fn($a) => ($a['tier'] ?? '') !== 'kontekstual');
        if (!empty($alerts) && empty($nonContextualAlerts)) {
            return [
                'matched' => false,
                'isWhitelist' => false,
                'contextualOnly' => true,
                'verdict' => 'ALLOW (CONTEXTUAL CHECK)',
                'score' => 85,
                'category' => 'belum_ditemukan_indikasi',
                'statusAlert' => 'Perlu Pemeriksaan Tambahan Kontekstual',
                'alertClass' => 'emerald',
                'title' => 'Bersih: Hanya Ditemukan Kata Kontekstual Tanpa Indikator Bahaya',
                'alerts' => []
            ];
        }

        if (!empty($alerts)) {
            $minPriority = min(array_column($alerts, 'priority'));
            $isHigh = ($minPriority === 1);

            return [
                'matched' => true,
                'isWhitelist' => false,
                'verdict' => $isHigh ? 'DROP / BLOKIR' : 'ALERT',
                'score' => $isHigh ? 15 : 45,
                'category' => $isHigh ? 'terindikasi_judol' : 'perlu_diwaspadai',
                'statusAlert' => $isHigh ? 'Peringatan Bahaya Tinggi (Suricata ALERT)' : 'Perlu Diwaspadai (Suricata SUSPICIOUS)',
                'alertClass' => $isHigh ? 'red' : 'amber',
                'title' => $isHigh ? 'Waspada: Terdeteksi Pelanggaran Signature Suricata IDS' : 'Perhatian: Parameter Mencurigakan pada Suricata IDS',
                'alerts' => $alerts
            ];
        }

        return [
            'matched' => false,
            'isWhitelist' => false,
            'verdict' => 'ALLOW',
            'score' => 85,
            'category' => 'belum_ditemukan_indikasi',
            'statusAlert' => 'Belum Terindikasi (Suricata CLEAN)',
            'alertClass' => 'emerald',
            'title' => 'Bersih: Tidak Ditemukan Pola Signature Berbahaya',
            'alerts' => []
        ];
    }
}


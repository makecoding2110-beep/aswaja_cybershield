<?php

namespace App\Services;

use App\DTO\AiAnalysisResultDTO;
use App\DTO\AiComparisonDTO;
use App\DTO\RiskAssessmentDTO;
use App\DTO\UrlAnalysisDTO;

/**
 * Service Penilaian Risiko dan Mesin Pengambil Keputusan (Risk Assessment Engine)
 * Menggabungkan temuan teknis URL, Suricata NIDS, dan hasil komparasi Dual AI
 * Sesuai Dokumen Spesifikasi Bagian 6 & Bagian 7
 */
class RiskAssessmentService
{
    /**
     * Mengevaluasi keputusan akhir berdasarkan Matriks Korelasi dan Bukti Teknis
     */
    public function evaluate(
        UrlAnalysisDTO $urlAnalysis,
        array $suricataResult,
        AiAnalysisResultDTO $gemini,
        ?AiAnalysisResultDTO $secondaryAi = null,
        ?AiComparisonDTO $aiComparison = null,
        ?array $threatResult = null
    ): RiskAssessmentDTO {
        $suricataMatched = (bool)($suricataResult['matched'] ?? false);
        $suricataAvailable = (bool)($suricataResult['available'] ?? false);

        $geminiSuccess = $gemini->isSuccess();
        $secondarySuccess = $secondaryAi ? $secondaryAi->isSuccess() : false;

        $geminiCat = $geminiSuccess ? $gemini->category : null;
        $secondaryCat = $secondarySuccess && $secondaryAi ? $secondaryAi->category : null;

        // Kumpulkan jejak evaluasi matriks
        $matrixTrace = [
            'suricata_state' => $suricataMatched ? 'mencurigakan' : ($suricataAvailable ? 'normal' : 'tidak_tersedia'),
            'gemini_state' => $geminiCat ?? 'tidak_tersedia',
            'ai_is_match' => $aiComparison ? $aiComparison->isMatch : $geminiSuccess,
            'url_has_typosquatting' => $urlAnalysis->hasTyposquatting,
            'url_is_suspicious_tld' => $urlAnalysis->isSuspiciousTld,
        ];

        // 1. Kumpulkan seluruh indikator gabungan (URL + Suricata + AI)
        $allIndicators = [];

        // Indikator Struktur URL
        foreach ($urlAnalysis->indicators as $ind) {
            $allIndicators[] = [
                'origin' => 'url_structure',
                'name' => $ind['name'],
                'evidence' => $ind['evidence'],
                'severity' => $ind['severity'],
                'explanation' => $ind['explanation']
            ];
        }

        // Indikator Suricata NIDS
        if (!empty($suricataResult['indicators'])) {
            foreach ($suricataResult['indicators'] as $ind) {
                $allIndicators[] = [
                    'origin' => 'suricata_nids',
                    'name' => $ind['name'],
                    'evidence' => $ind['evidence'],
                    'severity' => $ind['severity'],
                    'explanation' => $ind['explanation']
                ];
            }
        }

        // Indikator Dual AI
        foreach ($aiComparison->combinedIndicators as $ind) {
            $allIndicators[] = [
                'origin' => 'ai_' . $ind['source'],
                'name' => $ind['name'],
                'evidence' => $ind['evidence'],
                'severity' => $ind['severity'],
                'explanation' => $ind['explanation']
            ];
        }

        // Indikator Threat Analyzer (Defacement, Open Redirect, Typosquatting)
        if (!empty($threatResult['indicators'])) {
            foreach ($threatResult['indicators'] as $ind) {
                $allIndicators[] = [
                    'origin' => 'threat_analyzer',
                    'name' => $ind['name'],
                    'evidence' => $ind['evidence'],
                    'severity' => $ind['severity'],
                    'explanation' => $ind['explanation'],
                    'technique' => $ind['technique'] ?? null
                ];
            }
        }

        // 2. Analisis Tiga Tingkat Pencocokan Kata Kunci Judol & Hitung Nilai Anomali URL (0 - 100)
        $hasJudolStrong = false;
        $hasJudolMedium = false;
        $hasJudolContextual = false;
        $supportingIndicatorsCount = 0;

        $gamblingSupportingNames = [
            'suspicious_tld',
            'lucky_numbers_pattern',
            'deposit_promo_path',
            'referral_agent_param',
            'direct_ip_usage',
            'url_shortener',
            'repetitive_characters',
            'typosquatting_impersonation',
            'random_digits_domain',
            'deceptive_recency_year',
            'misleading_investment_path'
        ];

        foreach ($urlAnalysis->indicators as $ind) {
            $name = $ind['name'] ?? '';
            if ($name === 'judol_keyword_strong') {
                $hasJudolStrong = true;
            } elseif ($name === 'judol_keyword_medium') {
                $hasJudolMedium = true;
            } elseif ($name === 'judol_keyword_contextual') {
                $hasJudolContextual = true;
            } elseif (in_array($name, $gamblingSupportingNames, true)) {
                $supportingIndicatorsCount++;
            }
        }

        $urlScore = 0;
        if ($urlAnalysis->hasTyposquatting) $urlScore += 50;
        if ($urlAnalysis->isDirectIp) $urlScore += 30;
        if ($urlAnalysis->isShortener) $urlScore += 25;
        if ($urlAnalysis->isSuspiciousTld) $urlScore += 25;
        if ($hasJudolStrong) $urlScore += 55;
        if ($hasJudolMedium) $urlScore += ($supportingIndicatorsCount > 0 || $hasJudolContextual ? 45 : 25);
        if ($hasJudolContextual && ($supportingIndicatorsCount > 0 || $hasJudolStrong || $hasJudolMedium)) {
            $urlScore += 15;
        }
        if ($supportingIndicatorsCount >= 1) $urlScore += 20;
        $urlScore = min(100, $urlScore);

        // Evaluasi alert Suricata awal untuk judol dan investasi
        $alerts = $suricataResult['alerts'] ?? [];
        $hasPriority1Judol = false;
        $hasInvestasiAlert = false;
        foreach ($alerts as $a) {
            $msg = strtolower($a['msg'] ?? ($a['alert_signature'] ?? ''));
            $prio = (int)($a['priority'] ?? ($a['severity'] ?? 2));
            if (($prio === 1) && (str_contains($msg, 'judi') || str_contains($msg, 'slot') || str_contains($msg, 'maysir') || str_contains($msg, 'togel') || str_contains($msg, 'deposit') || str_contains($msg, 'uri') || str_contains($msg, 'dns'))) {
                $hasPriority1Judol = true;
            }
            if (str_contains($msg, 'investasi') || str_contains($msg, 'trading') || str_contains($msg, 'cuan')) {
                $hasInvestasiAlert = true;
            }
        }

        // 3. Evaluasi Matriks Korelasi (Spesifikasi Bagian 6)
        $finalCategory = 'belum_dapat_disimpulkan';
        $categoryLabel = 'Belum Dapat Disimpulkan';
        $alertClass = 'amber';
        $title = 'Perlu Pemeriksaan Lebih Lanjut';
        $decisionSource = 'unconcluded';

        // Kasus A: Kedua AI Sukses Menganalisis
        if ($geminiSuccess && $secondarySuccess && $secondaryAi) {
            // Skenario 1: Gemini Judol + Sekunder Judol
            if ($geminiCat === 'judol' && $secondaryCat === 'judol') {
                if ($suricataMatched || $urlScore >= 40) {
                    $finalCategory = 'terindikasi_judol';
                    $categoryLabel = 'Terindikasi Judi Online';
                    $alertClass = 'red';
                    $title = 'Waspada: Terindikasi Kuat Jaringan Perjudian Online';
                    $reasoning = 'Suricata dan model AI secara konsisten mengonfirmasi adanya indikator kuat aktivitas perjudian daring (slot/maysir).';
                    $decisionSource = 'parallel_dual_ai';
                } else {
                    $finalCategory = 'mencurigakan';
                    $categoryLabel = 'Mencurigakan (Perlu Pemeriksaan Tambahan)';
                    $alertClass = 'amber';
                    $title = 'Perhatian: Terindikasi Pola Judol pada Konten';
                    $reasoning = 'Model AI mendeteksi pola perjudian daring pada narasi/konten, namun belum memicu signature jaringan Suricata. Diperlukan bukti tambahan.';
                    $decisionSource = 'dual_ai_only';
                }
            }
            // Skenario 2: Gemini Investasi + Sekunder Investasi
            elseif ($geminiCat === 'investasi_menyesatkan' && $secondaryCat === 'investasi_menyesatkan') {
                if ($suricataMatched || $urlScore >= 40) {
                    $finalCategory = 'terindikasi_investasi';
                    $categoryLabel = 'Terindikasi Investasi Menyesatkan';
                    $alertClass = 'red';
                    $title = 'Waspada: Terindikasi Pola Penipuan Investasi Menyesatkan';
                    $reasoning = 'Bukti teknis dan analisis independen AI mengonfirmasi skema penawaran investasi bodong, klaim return tidak wajar, atau manipulasi finansial.';
                    $decisionSource = 'parallel_dual_ai';
                } else {
                    $finalCategory = 'mencurigakan';
                    $categoryLabel = 'Mencurigakan (Perlu Pemeriksaan Tambahan)';
                    $alertClass = 'amber';
                    $title = 'Perhatian: Terindikasi Janji Investasi Tidak Realistis';
                    $reasoning = 'Model AI mengidentifikasi penawaran investasi berisiko tinggi tanpa izin regulator, namun signature jaringan belum terpicu.';
                    $decisionSource = 'dual_ai_only';
                }
            }
            // Skenario 3: Kedua AI Normal
            elseif ($geminiCat === 'normal' && $secondaryCat === 'normal') {
                if ($suricataMatched || $urlScore >= 50) {
                    $finalCategory = 'mencurigakan';
                    $categoryLabel = 'Mencurigakan (Anomali Teknis)';
                    $alertClass = 'amber';
                    $title = 'Perhatian: Anomali Jaringan pada Domain yang Dinilai Wajar';
                    $reasoning = 'Sensor jaringan Suricata atau anomali URL mendeteksi indikator mencurigakan, meskipun AI menilai konten dalam batas normal. Perlu pemeriksaan teknis lanjutan.';
                    $decisionSource = 'suricata_discrepancy';
                } else {
                    $finalCategory = 'normal';
                    $categoryLabel = 'Normal';
                    $alertClass = 'emerald';
                    $title = 'Aman: Tidak Ditemukan Indikasi Ancaman';
                    $reasoning = 'Tidak ditemukan indikator perjudian online maupun investasi menyesatkan baik dari sensor jaringan Suricata maupun analisis AI.';
                    $decisionSource = 'clean_consensus';
                }
            }
            // Skenario 4: Perbedaan Pendapat AI (Disparitas: misal Judol vs Investasi)
            else {
                $finalCategory = 'belum_dapat_disimpulkan';
                $categoryLabel = 'Belum Dapat Disimpulkan';
                $alertClass = 'amber';
                $title = 'Klasifikasi Belum Pasti: Perlu Verifikasi Lanjutan';
                $reasoning = "Hasil analisis model berbeda (Utama: {$geminiCat}, Sekunder: {$secondaryCat}). Sistem menahan kesimpulan akhir dan menetapkan status verifikasi manual admin.";
                $decisionSource = 'ai_divergence';
            }
        }
        // Kasus B: Hanya Salah Satu AI yang Berhasil
        elseif ($geminiSuccess || $secondarySuccess) {
            $activeAi = $geminiSuccess ? $gemini : $secondaryAi;
            $isAiConfirmedJudol = in_array($activeAi->category, ['judol'], true)
                || ($activeAi->category === 'suspicious' && ($hasPriority1Judol || $hasJudolStrong || ($hasJudolMedium && $suricataMatched)));

            if ($isAiConfirmedJudol || $activeAi->category === 'investasi_menyesatkan') {
                $finalCategory = ($activeAi->category === 'investasi_menyesatkan') ? 'terindikasi_investasi' : 'terindikasi_judol';
                $categoryLabel = ($activeAi->category === 'investasi_menyesatkan') ? 'Terindikasi Investasi Menyesatkan' : 'Terindikasi Judi Online';
                $alertClass = 'red';
                $title = "Waspada: {$categoryLabel}";
                $reasoning = "Ditemukan bukti kuat (" . ($activeAi->reasoning ?: 'signature jaringan dan pola URL terkonfirmasi') . ").";
                $decisionSource = 'single_ai_with_evidence';
            } elseif ($suricataMatched || $urlScore >= 40 || $activeAi->category === 'suspicious') {
                $finalCategory = 'mencurigakan';
                $categoryLabel = 'Mencurigakan (Perlu Kewaspadaan)';
                $alertClass = 'amber';
                $title = 'Perhatian: Terdeteksi Pola Mencurigakan';
                $reasoning = "Ditemukan indikator mencurigakan pada tautan (" . ($activeAi->reasoning ?: 'sensor mendeteksi anomali pola') . "). Disarankan waspada.";
                $decisionSource = 'ai_and_technical_suspicion';
            } else {
                $finalCategory = 'normal';
                $categoryLabel = 'Belum Ditemukan Indikasi';
                $alertClass = 'emerald';
                $title = 'Bersih: Tidak Ditemukan Pola Signature Berbahaya';
                $reasoning = "Analisis {$activeAi->provider} dan sensor teknis mengonfirmasi tautan bersih dari indikasi ancaman siber.";
                $decisionSource = 'single_ai_clean';
            }
        }
        // Kasus C: Kedua AI Belum Terhubung (Offline)
        else {
            // Cek apakah Suricata hanya memicu rule tingkat menengah tanpa dukungan indikator lain
            $alerts = $suricataResult['alerts'] ?? [];
            $hasPriority1Judol = false;
            $hasInvestasiAlert = false;
            foreach ($alerts as $a) {
                $msg = strtolower($a['msg'] ?? ($a['alert_signature'] ?? ''));
                $prio = (int)($a['priority'] ?? ($a['severity'] ?? 2));
                if (($prio === 1) && (str_contains($msg, 'judi') || str_contains($msg, 'slot') || str_contains($msg, 'maysir') || str_contains($msg, 'togel') || str_contains($msg, 'deposit'))) {
                    $hasPriority1Judol = true;
                }
                if (str_contains($msg, 'investasi') || str_contains($msg, 'trading') || str_contains($msg, 'cuan')) {
                    $hasInvestasiAlert = true;
                }
            }

            foreach ($urlAnalysis->indicators as $ind) {
                $name = $ind['name'] ?? '';
                if ($name === 'misleading_investment_path') {
                    $hasInvestasiAlert = true;
                }
            }

            $isConfirmedJudol = $hasJudolStrong
                || $hasPriority1Judol
                || ($hasJudolMedium && ($supportingIndicatorsCount > 0 || $hasJudolContextual))
                || ($hasJudolContextual && $supportingIndicatorsCount >= 2);

            $isMediumStandalone = $hasJudolMedium && $supportingIndicatorsCount === 0 && !$hasJudolContextual && !$hasJudolStrong && !$hasPriority1Judol;

            if ($isConfirmedJudol) {
                $finalCategory = 'terindikasi_judol';
                $categoryLabel = 'Terindikasi Judi Online';
                $alertClass = 'red';
                $title = 'Waspada: Terindikasi Pola Judi Online';
                $reasoning = 'Sensor mendeteksi indikator kuat perjudian online atau kombinasi indikator menengah dengan bukti pendukung pada struktur tautan.';
                $decisionSource = 'technical_indicators_judol';
            } elseif ($hasInvestasiAlert) {
                $finalCategory = 'terindikasi_investasi';
                $categoryLabel = 'Terindikasi Investasi Menyesatkan';
                $alertClass = 'red';
                $title = 'Waspada: Terindikasi Pola Penawaran Investasi Menyesatkan';
                $reasoning = 'Sensor mendeteksi pola penawaran investasi menyesatkan atau janji keuntungan instan pada tautan.';
                $decisionSource = 'technical_indicators_investasi';
            } elseif ($isMediumStandalone) {
                $finalCategory = 'mencurigakan';
                $categoryLabel = 'Mencurigakan (Perlu Indikator Pendukung)';
                $alertClass = 'amber';
                $title = 'Perhatian: Ditemukan Indikator Tingkat Menengah (Perlu Bukti Pendukung)';
                $reasoning = 'Ditemukan kata kunci tingkat menengah (seperti maxwin/jackpot/scatter/freespin), namun belum disertai indikator pendukung lain.';
                $decisionSource = 'tier_medium_standalone';
            } elseif ($suricataMatched || $urlScore >= 20) {
                $finalCategory = 'mencurigakan';
                $categoryLabel = 'Mencurigakan (Sensor Teknis)';
                $alertClass = 'amber';
                $title = 'Peringatan: Terdeteksi Anomali Jaringan & URL';
                $reasoning = 'Sensor jaringan Suricata atau struktur URL mendeteksi indikasi berbahaya, namun kedua layanan AI belum terhubung untuk analisis kontekstual.';
                $decisionSource = 'suricata_ids_fallback';
            } else {
                $isWhitelist = (bool)($suricataResult['isWhitelist'] ?? false);
                if ($isWhitelist) {
                    $finalCategory = 'normal';
                    $categoryLabel = 'Status Aman Terverifikasi';
                    $alertClass = 'emerald';
                    $title = 'Aman: Terverifikasi oleh Suricata IDS Whitelist';
                    $reasoning = 'Domain terdaftar resmi dalam institusi pengawas pemerintah (OJK/Bappebti/Kominfo).';
                    $decisionSource = 'suricata_whitelist';
                } else {
                    $finalCategory = 'belum_ditemukan_indikasi';
                    $categoryLabel = 'Belum Ditemukan Indikasi';
                    $alertClass = 'emerald';
                    $title = 'Bersih: Tidak Ditemukan Pola Signature Berbahaya';
                    $reasoning = 'Tidak ditemukan indikasi pelanggaran signature pada tautan.';
                    $decisionSource = 'clean_technical';
                }
            }
        }

        // 3.B Evaluasi Penguatan dari Threat Analyzer (Defacement, Open Redirect, Typosquatting, URL Masking, Domain Rebouncer)
        $threatScore = (int)($threatResult['threat_score'] ?? 0);
        $detectedTechniques = array_values(array_unique(array_merge(
            $threatResult['techniques'] ?? [],
            $gemini->techniques ?? [],
            $secondaryAi ? ($secondaryAi->techniques ?? []) : []
        )));

        $hasInvestInd = false;
        foreach ($allIndicators as $ind) {
            $indName = $ind['name'] ?? '';
            if ($indName === 'binary_options_illegal_investment' || $indName === 'kamuflase_investasi_judol' || ($ind['technique'] ?? '') === 'kamuflase_investasi') {
                $hasInvestInd = true;
                break;
            }
        }

        if (!($suricataResult['isWhitelist'] ?? false) && $threatScore >= 60) {
            if (in_array('seo_cloaking', $detectedTechniques, true)) {
                $finalCategory = 'terindikasi_judol';
                $categoryLabel = 'Terindikasi Penyusupan (SEO Cloaking)';
                $alertClass = 'red';
                $title = 'Waspada: Terindikasi Penyusupan (SEO Cloaking)';
                $reasoning = "Situs resmi instansi pemerintah/akademik terbukti disusupi skrip SEO Cloaking sindikat judi online yang memanipulasi tampilan khusus untuk mesin pencari Googlebot.";
                $decisionSource = 'threat_analyzer';
            } elseif ($hasInvestInd || $geminiCat === 'investasi_menyesatkan') {
                $finalCategory = 'terindikasi_investasi';
                $categoryLabel = 'Terindikasi Investasi Menyesatkan';
                $alertClass = 'orange';
                $title = 'Waspada: Terindikasi Investasi Menyesatkan';
                $reasoning = in_array('url_masking', $detectedTechniques, true)
                    ? "Terdeteksi teknik URL Masking / Domain Rebouncer yang menyembunyikan situs trading binary options / investasi berisiko tinggi tanpa izin regulator."
                    : "Terdeteksi pola penawaran investasi berisiko tinggi / spekulasi ilegal tanpa izin regulator.";
                $decisionSource = 'threat_analyzer';
            } elseif ($threatScore >= 75) {
                $finalCategory = 'terindikasi_judol';
                $categoryLabel = 'Terindikasi Judi Online';
                $alertClass = 'red';
                $title = 'Waspada: Terindikasi Pola Eksploitasi / Penyusupan Tautan';
                $reasoning = "Terdeteksi teknik manipulasi tautan siber (" . implode(', ', $detectedTechniques) . ") yang membahayakan keamanan finansial pengguna.";
                $decisionSource = 'threat_analyzer';
            } elseif ($finalCategory === 'normal' || $finalCategory === 'belum_ditemukan_indikasi') {
                $finalCategory = 'mencurigakan';
                $categoryLabel = 'Mencurigakan';
                $alertClass = 'amber';
                $title = 'Perlu Pemeriksaan Lanjutan';
                $reasoning = "Terdeteksi anomali struktur tautan (" . implode(', ', $detectedTechniques) . "). Disarankan waspada.";
                $decisionSource = 'threat_analyzer';
            }
        }

        // 4. Kalkulasi Skor Risiko Terstandar & Terdokumentasi (0 - 100)
        $highestSuricataPriority = 3;
        foreach ($alerts as $a) {
            $p = (int)($a['priority'] ?? ($a['severity'] ?? 3));
            if ($p < $highestSuricataPriority) {
                $highestSuricataPriority = $p;
            }
        }

        $riskScore = $this->calculateConsistentRiskScore(
            $urlScore,
            $suricataMatched,
            $gemini,
            $secondaryAi,
            $finalCategory,
            $threatScore,
            $highestSuricataPriority
        );

        // 5. Penentuan Tingkat Risiko Berjenjang 4 Tingkat:
        // - Rendah (0% - 25%): Hijau
        // - Sedang (26% - 50%): Kuning / Amber
        // - Tinggi / Perlu Pemeriksaan (51% - 84%): Oranye
        // - Berat / Kritis (85% - 100%): Merah
        $riskLevel = match (true) {
            $riskScore >= 85 => 'berat',
            $riskScore >= 51 => 'tinggi',
            $riskScore >= 26 => 'sedang',
            default => 'rendah'
        };

        // Sinkronisasi kelas warna antarmuka pengguna
        $alertClass = match ($riskLevel) {
            'berat' => 'red',
            'tinggi' => 'orange',
            'sedang' => 'amber',
            default => 'emerald'
        };

        return new RiskAssessmentDTO(
            finalCategory: $finalCategory,
            categoryLabel: $categoryLabel,
            riskLevel: $riskLevel,
            riskScore: $riskScore,
            alertClass: $alertClass,
            title: $title,
            reasoning: $reasoning,
            allIndicators: $allIndicators,
            matrixTrace: $matrixTrace,
            decisionSource: $decisionSource,
            techniques: $detectedTechniques
        );
    }

    /**
     * Menghitung nilai skor risiko (Risk Score 0-100) secara konsisten dan terdokumentasi
     * Formula: Weighted Synthesis berbobot proporsional
     */
    protected function calculateConsistentRiskScore(
        int $urlAnomalyScore,
        bool $suricataMatched,
        AiAnalysisResultDTO $gemini,
        ?AiAnalysisResultDTO $secondaryAi = null,
        string $finalCategory = 'normal',
        int $threatScore = 0,
        int $highestSuricataPriority = 1
    ): int {
        // Jika kategori akhir normal / belum ditemukan indikasi dan tidak ada ancaman
        if (in_array($finalCategory, ['normal', 'belum_ditemukan_indikasi'], true) && $threatScore < 50) {
            return min(25, (int)round($urlAnomalyScore * 0.2));
        }

        $weights = [];
        $scores = [];

        // Bobot 1: Anomali Struktur URL (5%)
        $weights['url'] = 0.05;
        $scores['url'] = $urlAnomalyScore;

        // Bobot 2: Suricata NIDS (30%)
        // Prioritas 1 (Judol Eksplisit) -> 95; Prioritas 2 (TLD/Fast-flux) -> 50; Prioritas 3 (Afiliasi/Mitologi) -> 30
        $weights['suricata'] = 0.30;
        if ($suricataMatched) {
            $scores['suricata'] = match ($highestSuricataPriority) {
                1 => 95,
                2 => 50,
                default => 30
            };
        } else {
            $scores['suricata'] = 0;
        }

        // Bobot 3: UrlThreatAnalyzer (Defacement / Open Redirect / Typosquatting) (35%)
        if ($threatScore > 0) {
            $weights['threat'] = 0.35;
            $scores['threat'] = $threatScore;
        }

        // Bobot 4: Gemini AI (30%)
        if ($gemini->isSuccess()) {
            $weights['gemini'] = 0.30;
            $scores['gemini'] = $gemini->riskScore;
        }

        // Bobot 5: Model Tambahan (Opsional)
        if ($secondaryAi && $secondaryAi->isSuccess()) {
            $weights['secondary_ai'] = 0.10;
            $scores['secondary_ai'] = $secondaryAi->riskScore;
        }

        // Normalisasi total bobot menjadi 1.0
        $totalWeight = array_sum($weights);
        if ($totalWeight <= 0) {
            return 50; // Netral
        }

        $computed = 0.0;
        foreach ($weights as $key => $w) {
            $normWeight = $w / $totalWeight;
            $computed += ($scores[$key] * $normWeight);
        }

        // Penyesuaian khusus jika terindikasi kuat judol / investasi
        if (in_array($finalCategory, ['terindikasi_judol', 'terindikasi_investasi'], true)) {
            $computed = max(70, $computed, $threatScore);
        } elseif ($finalCategory === 'belum_dapat_disimpulkan') {
            // Rentang waspada seimbang
            $computed = max(40, min(65, (int)round($computed)));
        }

        return max(0, min(100, (int)round($computed)));
    }
}

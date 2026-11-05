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
        AiAnalysisResultDTO $deepseek,
        AiComparisonDTO $aiComparison
    ): RiskAssessmentDTO {
        $suricataMatched = (bool)($suricataResult['matched'] ?? false);
        $suricataAvailable = (bool)($suricataResult['available'] ?? false);

        $geminiSuccess = $gemini->isSuccess();
        $deepseekSuccess = $deepseek->isSuccess();

        $geminiCat = $geminiSuccess ? $gemini->category : null;
        $deepseekCat = $deepseekSuccess ? $deepseek->category : null;

        // Kumpulkan jejak evaluasi matriks
        $matrixTrace = [
            'suricata_state' => $suricataMatched ? 'mencurigakan' : ($suricataAvailable ? 'normal' : 'tidak_tersedia'),
            'gemini_state' => $geminiCat ?? 'tidak_tersedia',
            'deepseek_state' => $deepseekCat ?? 'tidak_tersedia',
            'ai_is_match' => $aiComparison->isMatch,
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

        // 3. Evaluasi Matriks Korelasi (Spesifikasi Bagian 6)
        $finalCategory = 'belum_dapat_disimpulkan';
        $categoryLabel = 'Belum Dapat Disimpulkan';
        $alertClass = 'amber';
        $title = 'Perlu Pemeriksaan Lebih Lanjut';
        $decisionSource = 'unconcluded';

        // Kasus A: Kedua AI Sukses Menganalisis
        if ($geminiSuccess && $deepseekSuccess) {
            // Skenario 1: Gemini Judol + DeepSeek Judol
            if ($geminiCat === 'judol' && $deepseekCat === 'judol') {
                if ($suricataMatched || $urlScore >= 40) {
                    $finalCategory = 'terindikasi_judol';
                    $categoryLabel = 'Terindikasi Judi Online';
                    $alertClass = 'red';
                    $title = 'Waspada: Terindikasi Kuat Jaringan Perjudian Online';
                    $reasoning = 'Suricata dan kedua model AI (Gemini & DeepSeek) secara konsisten mengonfirmasi adanya indikator kuat aktivitas perjudian daring (slot/maysir).';
                    $decisionSource = 'parallel_dual_ai';
                } else {
                    $finalCategory = 'mencurigakan';
                    $categoryLabel = 'Mencurigakan (Perlu Pemeriksaan Tambahan)';
                    $alertClass = 'amber';
                    $title = 'Perhatian: Terindikasi Pola Judol pada Konten';
                    $reasoning = 'Kedua AI mendeteksi pola perjudian daring pada narasi/konten, namun belum memicu signature jaringan Suricata. Diperlukan bukti tambahan.';
                    $decisionSource = 'dual_ai_only';
                }
            }
            // Skenario 2: Gemini Investasi + DeepSeek Investasi
            elseif ($geminiCat === 'investasi_menyesatkan' && $deepseekCat === 'investasi_menyesatkan') {
                if ($suricataMatched || $urlScore >= 40) {
                    $finalCategory = 'terindikasi_investasi';
                    $categoryLabel = 'Terindikasi Investasi Menyesatkan';
                    $alertClass = 'red';
                    $title = 'Waspada: Terindikasi Pola Penipuan Investasi Menyesatkan';
                    $reasoning = 'Bukti teknis dan analisis independen kedua AI mengonfirmasi skema penawaran investasi bodong, klaim return tidak wajar, atau manipulasi finansial.';
                    $decisionSource = 'parallel_dual_ai';
                } else {
                    $finalCategory = 'mencurigakan';
                    $categoryLabel = 'Mencurigakan (Perlu Pemeriksaan Tambahan)';
                    $alertClass = 'amber';
                    $title = 'Perhatian: Terindikasi Janji Investasi Tidak Realistis';
                    $reasoning = 'Kedua AI mengidentifikasi penawaran investasi berisiko tinggi tanpa izin regulator, namun signature jaringan belum terpicu.';
                    $decisionSource = 'dual_ai_only';
                }
            }
            // Skenario 3: Kedua AI Normal
            elseif ($geminiCat === 'normal' && $deepseekCat === 'normal') {
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
                    $reasoning = 'Tidak ditemukan indikator perjudian online maupun investasi menyesatkan baik dari sensor jaringan Suricata maupun analisis independen Dual AI.';
                    $decisionSource = 'clean_consensus';
                }
            }
            // Skenario 4: Perbedaan Pendapat AI (Disparitas: misal Judol vs Investasi)
            else {
                $finalCategory = 'belum_dapat_disimpulkan';
                $categoryLabel = 'Belum Dapat Disimpulkan';
                $alertClass = 'amber';
                $title = 'Klasifikasi Belum Pasti: Disparitas Analisis AI';
                $reasoning = "Hasil analisis kedua model AI berbeda (Gemini: {$geminiCat}, DeepSeek: {$deepseekCat}). Sistem menahan kesimpulan akhir dan menetapkan status verifikasi manual admin.";
                $decisionSource = 'ai_divergence';
            }
        }
        // Kasus B: Hanya Salah Satu AI yang Berhasil
        elseif ($geminiSuccess || $deepseekSuccess) {
            $activeAi = $geminiSuccess ? $gemini : $deepseek;
            if (in_array($activeAi->category, ['judol', 'investasi_menyesatkan'], true)) {
                $finalCategory = ($activeAi->category === 'judol') ? 'terindikasi_judol' : 'terindikasi_investasi';
                $categoryLabel = ($activeAi->category === 'judol') ? 'Terindikasi Judi Online' : 'Terindikasi Investasi Menyesatkan';
                $alertClass = 'red';
                $title = "Waspada: Terindikasi {$categoryLabel} (Single AI)";
                $reasoning = "Ditemukan bukti kuat dari {$activeAi->provider} ({$activeAi->reasoning}), namun model pasangan sedang tidak tersedia untuk verifikasi silang.";
                $decisionSource = 'single_ai_with_evidence';
            } else {
                $finalCategory = 'mencurigakan';
                $categoryLabel = 'Mencurigakan';
                $alertClass = 'amber';
                $title = 'Perlu Pemeriksaan Lanjutan';
                $reasoning = 'Hasil satu AI belum dapat diverifikasi silang karena keterbatasan koneksi model pasangan.';
                $decisionSource = 'single_ai_unverified';
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

        // 4. Kalkulasi Skor Risiko Terstandar & Terdokumentasi (0 - 100)
        $riskScore = $this->calculateConsistentRiskScore(
            $urlScore,
            $suricataMatched,
            $gemini,
            $deepseek,
            $finalCategory
        );

        // 5. Penentuan Tingkat Risiko (Rendah, Sedang, Tinggi, Kritis)
        $riskLevel = match (true) {
            $riskScore >= 86 => 'kritis',
            $riskScore >= 61 => 'tinggi',
            $riskScore >= 31 => 'sedang',
            default => 'rendah'
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
            decisionSource: $decisionSource
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
        AiAnalysisResultDTO $deepseek,
        string $finalCategory
    ): int {
        // Jika kategori akhir normal
        if ($finalCategory === 'normal') {
            return min(25, (int)round($urlAnomalyScore * 0.2));
        }

        $weights = [];
        $scores = [];

        // Bobot 1: Anomali Struktur URL (5%)
        $weights['url'] = 0.05;
        $scores['url'] = $urlAnomalyScore;

        // Bobot 2: Suricata NIDS (35%)
        $weights['suricata'] = 0.35;
        $scores['suricata'] = $suricataMatched ? 95 : 0;

        // Bobot 3: Gemini AI (30%)
        if ($gemini->isSuccess()) {
            $weights['gemini'] = 0.30;
            $scores['gemini'] = $gemini->riskScore;
        }

        // Bobot 4: DeepSeek AI (30%)
        if ($deepseek->isSuccess()) {
            $weights['deepseek'] = 0.30;
            $scores['deepseek'] = $deepseek->riskScore;
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
            $computed = max(70, $computed);
        } elseif ($finalCategory === 'belum_dapat_disimpulkan') {
            // Rentang waspada seimbang
            $computed = max(40, min(65, (int)round($computed)));
        }

        return max(0, min(100, (int)round($computed)));
    }
}

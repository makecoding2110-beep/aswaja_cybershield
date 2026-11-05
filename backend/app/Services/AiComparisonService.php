<?php

namespace App\Services;

use App\DTO\AiAnalysisResultDTO;
use App\DTO\AiComparisonDTO;

/**
 * Service Pembanding Hasil Analisis Dual AI (Google Gemini vs DeepSeek)
 * Menganalisis kesesuaian kategori, disparitas skor risiko, dan konsistensi bukti temuan.
 * Sesuai Dokumen Spesifikasi Bagian 5.3 & Bagian 6
 */
class AiComparisonService
{
    /**
     * Membandingkan hasil output Gemini dan DeepSeek secara objektif
     */
    public function compare(AiAnalysisResultDTO $gemini, AiAnalysisResultDTO $deepseek): AiComparisonDTO
    {
        $geminiSuccess = $gemini->isSuccess();
        $deepseekSuccess = $deepseek->isSuccess();
        $bothAvailable = $geminiSuccess && $deepseekSuccess;

        $discrepancyDetails = [];
        $combinedIndicators = [];

        // 1. Kumpulkan indikator dari Gemini
        if ($geminiSuccess) {
            foreach ($gemini->indicators as $ind) {
                $combinedIndicators[] = [
                    'source' => 'gemini',
                    'name' => $ind['name'],
                    'evidence' => $ind['evidence'],
                    'severity' => $ind['severity'],
                    'explanation' => "{$ind['explanation']} [Analisis Gemini]"
                ];
            }
        } else {
            $discrepancyDetails[] = "Layanan Google Gemini: {$gemini->status} ({$gemini->reasoning})";
        }

        // 2. Kumpulkan indikator dari DeepSeek
        if ($deepseekSuccess) {
            foreach ($deepseek->indicators as $ind) {
                // Periksa apakah evidence yang identik sudah dicatat
                $isDuplicate = false;
                foreach ($combinedIndicators as $existing) {
                    if ($existing['source'] === 'gemini' && strtolower($existing['evidence']) === strtolower($ind['evidence'])) {
                        $isDuplicate = true;
                        break;
                    }
                }

                if (!$isDuplicate) {
                    $combinedIndicators[] = [
                        'source' => 'deepseek',
                        'name' => $ind['name'],
                        'evidence' => $ind['evidence'],
                        'severity' => $ind['severity'],
                        'explanation' => "{$ind['explanation']} [Penalaran DeepSeek]"
                    ];
                }
            }
        } else {
            $discrepancyDetails[] = "Layanan DeepSeek: {$deepseek->status} ({$deepseek->reasoning})";
        }

        // 3. Evaluasi Kasus Ketersediaan
        if (!$bothAvailable) {
            $scoreDelta = 0;
            $isMatch = false;
            $agreedCategory = null;
            $hasDiscrepancy = true;

            if ($geminiSuccess && !$deepseekSuccess) {
                $summary = "Hanya Google Gemini yang berhasil menganalisis ({$gemini->category}, skor risiko {$gemini->riskScore}%). DeepSeek tidak tersedia.";
                $agreedCategory = $gemini->category;
            } elseif (!$geminiSuccess && $deepseekSuccess) {
                $summary = "Hanya DeepSeek yang berhasil menganalisis ({$deepseek->category}, skor risiko {$deepseek->riskScore}%). Gemini tidak tersedia.";
                $agreedCategory = $deepseek->category;
            } else {
                $summary = "Kedua layanan AI tidak tersedia atau belum dikonfigurasi. Pemeriksaan mandiri bergantung pada sensor teknis.";
            }

            return new AiComparisonDTO(
                bothAvailable: false,
                isMatch: false,
                agreedCategory: $agreedCategory,
                scoreDelta: $scoreDelta,
                hasDiscrepancy: $hasDiscrepancy,
                discrepancyDetails: $discrepancyDetails,
                combinedIndicators: $combinedIndicators,
                summary: $summary
            );
        }

        // 4. Kedua AI Tersedia: Evaluasi Kesepakatan
        $scoreDelta = abs($gemini->riskScore - $deepseek->riskScore);
        $isMatch = ($gemini->category === $deepseek->category);
        $agreedCategory = $isMatch ? $gemini->category : null;
        $hasDiscrepancy = !$isMatch || ($scoreDelta > 35);

        if ($isMatch) {
            if ($scoreDelta <= 15) {
                $summary = "Kedua AI sepakat secara konsisten pada kategori '{$gemini->category}' dengan disparitas skor sangat rendah ({$scoreDelta}%).";
            } else {
                $summary = "Kedua AI sepakat pada kategori '{$gemini->category}', namun terdapat perbedaan intensitas risiko (selisih skor {$scoreDelta}%).";
                $discrepancyDetails[] = "Disparitas skor risiko: Gemini={$gemini->riskScore}%, DeepSeek={$deepseek->riskScore}% (selisih {$scoreDelta}%).";
            }
        } else {
            $discrepancyDetails[] = "Perbedaan kategori: Gemini mengidentifikasi '{$gemini->category}', sedangkan DeepSeek mengidentifikasi '{$deepseek->category}'.";
            $summary = "Terdapat disparitas keputusan antara Gemini ('{$gemini->category}') dan DeepSeek ('{$deepseek->category}'). Diperlukan verifikasi bukti tambahan.";
        }

        return new AiComparisonDTO(
            bothAvailable: true,
            isMatch: $isMatch,
            agreedCategory: $agreedCategory,
            scoreDelta: $scoreDelta,
            hasDiscrepancy: $hasDiscrepancy,
            discrepancyDetails: $discrepancyDetails,
            combinedIndicators: $combinedIndicators,
            summary: $summary
        );
    }
}


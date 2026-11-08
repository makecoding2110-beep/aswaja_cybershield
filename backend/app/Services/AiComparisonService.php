<?php

namespace App\Services;

use App\DTO\AiAnalysisResultDTO;
use App\DTO\AiComparisonDTO;

/**
 * Service Analisis & Konsistensi Temuan AI (Google Gemini AI)
 * Menganalisis kesesuaian kategori, skor risiko, dan konsistensi bukti temuan.
 * Sesuai Dokumen Spesifikasi Bagian 5.3 & Bagian 6
 */
class AiComparisonService
{
    /**
     * Memeriksa dan merangkum hasil analisis AI secara objektif
     */
    public function compare(AiAnalysisResultDTO $gemini, ?AiAnalysisResultDTO $secondaryAi = null): AiComparisonDTO
    {
        $geminiSuccess = $gemini->isSuccess();
        $secondarySuccess = $secondaryAi ? $secondaryAi->isSuccess() : false;
        $bothAvailable = $geminiSuccess && $secondarySuccess;

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
                    'explanation' => $ind['explanation']
                ];
            }
        } else {
            $discrepancyDetails[] = "Layanan Analisis Gemini: {$gemini->status} ({$gemini->reasoning})";
        }

        // 2. Kumpulkan indikator dari model sekunder (jika ada)
        if ($secondarySuccess && $secondaryAi) {
            foreach ($secondaryAi->indicators as $ind) {
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
                        'source' => 'ai_secondary',
                        'name' => $ind['name'],
                        'evidence' => $ind['evidence'],
                        'severity' => $ind['severity'],
                        'explanation' => $ind['explanation']
                    ];
                }
            }
        }

        // 3. Evaluasi Kasus Ketersediaan
        if (!$bothAvailable) {
            $scoreDelta = 0;
            $isMatch = $geminiSuccess;
            $agreedCategory = $geminiSuccess ? $gemini->category : null;
            $hasDiscrepancy = false;

            if ($geminiSuccess) {
                $summary = "Analisis mandiri Google Gemini AI ({$gemini->category}, skor risiko {$gemini->riskScore}%).";
            } else {
                $summary = "Layanan AI tidak tersedia atau belum dikonfigurasi. Pemeriksaan mandiri bergantung pada sensor teknis.";
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
        $scoreDelta = abs($gemini->riskScore - $secondaryAi->riskScore);
        $isMatch = ($gemini->category === $secondaryAi->category);
        $agreedCategory = $isMatch ? $gemini->category : null;
        $hasDiscrepancy = !$isMatch || ($scoreDelta > 35);

        if ($isMatch) {
            if ($scoreDelta <= 15) {
                $summary = "Model AI sepakat secara konsisten pada kategori '{$gemini->category}' dengan disparitas skor sangat rendah ({$scoreDelta}%).";
            } else {
                $summary = "Model AI sepakat pada kategori '{$gemini->category}', namun terdapat perbedaan intensitas risiko (selisih skor {$scoreDelta}%).";
                $discrepancyDetails[] = "Disparitas skor risiko: Utama={$gemini->riskScore}%, Sekunder={$secondaryAi->riskScore}% (selisih {$scoreDelta}%).";
            }
        } else {
            $discrepancyDetails[] = "Perbedaan kategori: Utama mengidentifikasi '{$gemini->category}', sedangkan Sekunder mengidentifikasi '{$secondaryAi->category}'.";
            $summary = "Terdapat disparitas keputusan antara model utama ('{$gemini->category}') dan model sekunder ('{$secondaryAi->category}'). Diperlukan verifikasi bukti tambahan.";
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


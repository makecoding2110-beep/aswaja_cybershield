<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiOrchestratorService;
use Illuminate\Http\JsonResponse;

class AiIntegrationController extends Controller
{
    public function __construct(protected AiOrchestratorService $aiService) {}

    /**
     * Mengambil status konfigurasi Integrasi AI (Google Gemini) yang aman.
     */
    public function status(): JsonResponse
    {
        $status = $this->aiService->getStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Menjalankan pengujian probe koneksi Dual AI.
     */
    public function testConnection(): JsonResponse
    {
        $result = $this->aiService->testConnection();

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiOrchestratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyzeLinkController extends Controller
{
    public function __construct(protected AiOrchestratorService $aiService) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|string|max:2048'
        ], [
            'url.required' => 'URL tautan yang akan dianalisis wajib diisi.',
            'url.max' => 'Panjang karakter URL melebihi batas maksimal.'
        ]);

        $url = trim($request->input('url'));

        // Basic normalization: prepend https:// if missing scheme
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        try {
            $dto = $this->aiService->analyzeLink($url);
            return response()->json($dto->toArray());
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kendala saat menganalisis tautan: ' . $e->getMessage()
            ], 500);
        }
    }
}


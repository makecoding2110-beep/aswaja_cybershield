<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Prinsip4TController extends Controller
{
    public function __construct(protected MockDataRepository $repository) {}

    public function index(): JsonResponse
    {
        $items = $this->repository->getPrinsip4T();
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function update(Request $request, string|int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:150',
            'name' => 'nullable|string|max:150',
            'subtitle' => 'nullable|string|max:150',
            'tagline' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'link' => 'nullable|string|max:255'
        ]);

        $updated = $this->repository->updatePrinsip4T($id, $validated);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Kaidah 4T tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Materi kaidah {$updated['name']} berhasil diperbarui.",
            'data' => $updated
        ]);
    }
}


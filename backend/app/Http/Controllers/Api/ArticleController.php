<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(protected MockDataRepository $repository) {}

    public function index(): JsonResponse
    {
        $articles = $this->repository->getArticles();
        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:2000',
            'summary' => 'nullable|string|max:2000',
            'author' => 'nullable|string|max:150',
            'status' => 'nullable|string|in:Published,Draft',
            'readTime' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'coverImage' => 'nullable|string'
        ]);

        $newArticle = $this->repository->createArticle($validated);

        return response()->json([
            'success' => true,
            'message' => 'Artikel edukasi berhasil ditambahkan.',
            'data' => $newArticle
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $articles = $this->repository->getArticles();
        foreach ($articles as $article) {
            if ($article['id'] === $id) {
                return response()->json([
                    'success' => true,
                    'data' => $article
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Artikel tidak ditemukan.'
        ], 404);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:2000',
            'summary' => 'nullable|string|max:2000',
            'author' => 'nullable|string|max:150',
            'status' => 'nullable|string|in:Published,Draft',
            'readTime' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'coverImage' => 'nullable|string'
        ]);

        $updated = $this->repository->updateArticle($id, $validated);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diperbarui.',
            'data' => $updated
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->repository->deleteArticle($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus.'
        ]);
    }
}

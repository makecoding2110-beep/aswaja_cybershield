<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function __construct(protected MockDataRepository $repository) {}

    public function index(): JsonResponse
    {
        $cases = $this->repository->getCaseStudies();
        return response()->json([
            'success' => true,
            'data' => $cases
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'scenario' => 'required|string',
            'sender' => 'nullable|string|max:150',
            'message' => 'nullable|string',
            'riskLevel' => 'nullable|string|in:Kritis,Tinggi,Sedang',
            'fiqihPrinciple' => 'nullable|string|max:150',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.key' => 'required|string',
            'options.*.text' => 'required|string',
            'options.*.correct' => 'required|boolean',
            'discussion' => 'required|string'
        ]);

        $newCase = $this->repository->createCaseStudy($validated);

        return response()->json([
            'success' => true,
            'message' => 'Soal studi kasus berhasil ditambahkan.',
            'data' => $newCase
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $cases = $this->repository->getCaseStudies();
        foreach ($cases as $case) {
            if ($case['id'] === $id) {
                return response()->json([
                    'success' => true,
                    'data' => $case
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Studi kasus tidak ditemukan.'
        ], 404);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'scenario' => 'nullable|string',
            'sender' => 'nullable|string|max:150',
            'message' => 'nullable|string',
            'riskLevel' => 'nullable|string|in:Kritis,Tinggi,Sedang',
            'fiqihPrinciple' => 'nullable|string|max:150',
            'question' => 'nullable|string',
            'options' => 'nullable|array|min:2',
            'options.*.key' => 'required|string',
            'options.*.text' => 'required|string',
            'options.*.correct' => 'required|boolean',
            'discussion' => 'nullable|string'
        ]);

        $updated = $this->repository->updateCaseStudy($id, $validated);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Studi kasus tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Studi kasus berhasil diperbarui.',
            'data' => $updated
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->repository->deleteCaseStudy($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Studi kasus tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Studi kasus berhasil dihapus.'
        ]);
    }

    /**
     * Evaluasi jawaban kuis Cyber Guard di sisi backend untuk mencegah kecurangan.
     */
    public function evaluateQuiz(Request $request): JsonResponse
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.caseId' => 'required|integer',
            'answers.*.selectedKey' => 'required|string'
        ]);

        $cases = $this->repository->getCaseStudies();
        $casesById = [];
        foreach ($cases as $c) {
            $casesById[$c['id']] = $c;
        }

        $total = count($request->input('answers'));
        $correctCount = 0;
        $evaluations = [];

        foreach ($request->input('answers') as $ans) {
            $cId = (int) $ans['caseId'];
            $selectedKey = strtoupper(trim($ans['selectedKey']));

            if (!isset($casesById[$cId])) {
                continue;
            }

            $case = $casesById[$cId];
            $correctOption = null;
            foreach ($case['options'] as $opt) {
                if ($opt['correct']) {
                    $correctOption = $opt['key'];
                    break;
                }
            }

            $isCorrect = ($selectedKey === $correctOption);
            if ($isCorrect) {
                $correctCount++;
            }

            $evaluations[] = [
                'caseId' => $cId,
                'isCorrect' => $isCorrect,
                'correctKey' => $correctOption,
                'discussion' => $case['discussion']
            ];
        }

        $finalScore = $total > 0 ? (int) round(($correctCount / $total) * 100) : 0;

        return response()->json([
            'success' => true,
            'score' => $finalScore,
            'correctCount' => $correctCount,
            'total' => $total,
            'evaluations' => $evaluations
        ]);
    }
}


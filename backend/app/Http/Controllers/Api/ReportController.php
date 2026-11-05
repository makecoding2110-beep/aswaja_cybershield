<?php

namespace App\Http\Controllers\Api;

use App\DTO\ReportDTO;
use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use App\Services\AiOrchestratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected AiOrchestratorService $aiOrchestrator;

    public function __construct(
        protected MockDataRepository $repository,
        ?AiOrchestratorService $aiOrchestrator = null
    ) {
        $this->aiOrchestrator = $aiOrchestrator ?? app(AiOrchestratorService::class);
    }

    public function index(): JsonResponse
    {
        $reports = $this->repository->getReports();
        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'url' => 'required|string|max:2048',
            'reporter' => 'nullable|string|max:100',
            'nama' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:150',
            'kategori' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:5000',
            'deskripsi' => 'nullable|string|max:5000',
            'evidence' => 'nullable|file|mimes:png,jpg,jpeg,webp,gif,pdf|max:5120'
        ], [
            'url.required' => 'URL tautan yang dilaporkan wajib diisi.',
            'evidence.max' => 'Ukuran berkas tangkapan layar maksimal 5MB.'
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence') && $request->file('evidence')->isValid()) {
            $evidencePath = $request->file('evidence')->store('evidence', 'public');
        }

        $dto = ReportDTO::fromRequest($request->all(), $evidencePath);

        // Audit Forensik Otomatis via DeepSeek AI / Multi-Agent
        $aiAnalysis = null;
        try {
            $aiAnalysis = $this->aiOrchestrator->analyzeReport([
                'url' => $dto->url,
                'category' => $dto->category,
                'description' => $dto->description,
                'reporter' => $dto->reporter
            ]);
        } catch (\Throwable $e) {
            // Silently continue if AI service is temporarily offline
        }

        $verificationNotes = $dto->verificationNotes;
        if (empty($verificationNotes) && !empty($aiAnalysis)) {
            $sourceLabel = ($aiAnalysis['source'] ?? '') === 'deepseek_ai' ? 'Audit DeepSeek AI' : 'Triase AI Otomatis';
            $verificationNotes = "[{$sourceLabel}] Tingkat Risiko: " . ($aiAnalysis['riskLevel'] ?? 'Sedang') . " | Modus: " . ($aiAnalysis['modusOperandi'] ?? '');
        }

        $newReport = $this->repository->createReport([
            'url' => $dto->url,
            'reporter' => $dto->reporter,
            'category' => $dto->category,
            'description' => $dto->description,
            'status' => $dto->status,
            'verificationNotes' => $verificationNotes,
            'handledBy' => $dto->handledBy,
            'evidencePath' => $dto->evidencePath,
            'aiAnalysis' => $aiAnalysis
        ]);

        return response()->json([
            'success' => true,
            'ticketId' => $newReport['id'],
            'message' => 'Laporan Anda telah berhasil dicatat dan masuk antrean verifikasi siber Aswaja.',
            'aiAnalysis' => $aiAnalysis,
            'data' => $newReport
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $reports = $this->repository->getReports();
        foreach ($reports as $report) {
            if ($report['id'] === $id) {
                return response()->json([
                    'success' => true,
                    'data' => $report
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Data laporan tidak ditemukan.'
        ], 404);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'nullable|string|in:Masuk,Pending,Diproses,Selesai,Ditolak',
            'verificationNotes' => 'nullable|string|max:2000',
            'verification_notes' => 'nullable|string|max:2000',
            'assignedAdmin' => 'nullable|string|max:100',
            'handledBy' => 'nullable|string|max:100',
        ]);

        $updated = $this->repository->updateReport($id, $request->all());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => "Laporan dengan ID {$id} tidak ditemukan."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status laporan berhasil diperbarui.',
            'data' => $updated
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->repository->deleteReport($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => "Laporan dengan ID {$id} tidak ditemukan."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Laporan {$id} berhasil dihapus permanen."
        ]);
    }
}


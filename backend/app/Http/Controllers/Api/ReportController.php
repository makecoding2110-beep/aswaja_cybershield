<?php

namespace App\Http\Controllers\Api;

use App\DTO\ReportDTO;
use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use App\Services\AiOrchestratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        // 1. Pembatasan Rate Limit: Maksimal 2x pelaporan setiap 2 jam (7200 detik) untuk pengunjung publik
        $isAdmin = !empty($request->bearerToken()) || $request->user();
        $rateLimitKey = 'report_submission:' . ($request->ip() ?: 'guest');

        if (!$isAdmin && RateLimiter::tooManyAttempts($rateLimitKey, 2)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = max(1, (int) ceil($seconds / 60));
            $hours = (int) floor($minutes / 60);
            $remMins = $minutes % 60;
            $timeString = $hours > 0 ? "{$hours} jam {$remMins} menit" : "{$minutes} menit";

            return response()->json([
                'success' => false,
                'status' => 'rate_limited',
                'error' => 'Batas kuota pelaporan tercapai (Maksimal 2x setiap 2 jam).',
                'message' => "Anda telah mencapai batas maksimal 2 laporan dalam 2 jam terakhir. Demi keamanan dan efisiensi sistem, silakan coba kembali dalam {$timeString}.",
                'retry_after_seconds' => $seconds,
                'retry_after_minutes' => $minutes,
                'time_string' => $timeString,
                'quota' => [
                    'max' => 2,
                    'period' => '2 jam',
                    'remaining' => 0,
                    'retry_in' => $timeString,
                ],
                'reasons' => [
                    [
                        'title' => 'Mencegah Serangan Spam dan DDoS',
                        'desc' => 'Membatasi frekuensi kiriman (request) mencegah pihak tidak bertanggung jawab atau bot otomatis membanjiri server dengan ribuan laporan palsu dalam waktu singkat yang bisa membuat website menjadi lambat atau tumbang (down).'
                    ],
                    [
                        'title' => 'Efisiensi Validasi Tim Penyelidik',
                        'desc' => 'Menghindari penumpukan laporan duplikat atau berulang-ulang untuk masalah yang sama. Hal ini membantu tim admin atau investigator fokus memproses laporan yang benar-benar valid dan unik.'
                    ],
                    [
                        'title' => 'Optimalisasi Bandwidth dan Penyimpanan',
                        'desc' => 'Menghemat ruang penyimpanan basis data (database) server dari data sampah atau spam yang tidak perlu.'
                    ],
                    [
                        'title' => 'Mendorong Kualitas Laporan',
                        'desc' => 'Memastikan pengguna lebih berhati-hati, detail, dan serius saat menyusun isi laporan mereka karena adanya keterbatasan kuota pengiriman.'
                    ],
                ]
            ], 429);
        }

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

        // Audit Forensik Otomatis via Multi-Agent AI
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
            $sourceLabel = 'Triase AI Otomatis';
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

        if (!$isAdmin) {
            RateLimiter::hit($rateLimitKey, 7200); // Kunci 2 jam (7200 detik)
        }
        $attempts = $isAdmin ? 0 : RateLimiter::attempts($rateLimitKey);
        $remaining = $isAdmin ? 999 : max(0, 2 - $attempts);

        return response()->json([
            'success' => true,
            'ticketId' => $newReport['id'],
            'message' => 'Laporan Anda telah berhasil dicatat dan masuk antrean verifikasi siber Aswaja.',
            'aiAnalysis' => $aiAnalysis,
            'data' => $newReport,
            'quota' => [
                'max' => 2,
                'period' => '2 jam',
                'used' => $attempts,
                'remaining' => $remaining
            ]
        ], 201);
    }

    /**
     * Cek status sisa kuota pelaporan pengguna saat ini
     */
    public function quota(Request $request): JsonResponse
    {
        $isAdmin = !empty($request->bearerToken()) || $request->user();
        if ($isAdmin) {
            return response()->json([
                'success' => true,
                'is_admin' => true,
                'max' => 2,
                'used' => 0,
                'remaining' => 999,
                'retry_after_seconds' => 0,
                'retry_after_minutes' => 0,
            ]);
        }

        $rateLimitKey = 'report_submission:' . ($request->ip() ?: 'guest');
        $attempts = RateLimiter::attempts($rateLimitKey);
        $remaining = max(0, 2 - $attempts);
        $seconds = RateLimiter::availableIn($rateLimitKey);
        $minutes = $seconds > 0 ? max(1, (int) ceil($seconds / 60)) : 0;
        $hours = (int) floor($minutes / 60);
        $remMins = $minutes % 60;
        $timeString = $hours > 0 ? "{$hours} jam {$remMins} menit" : "{$minutes} menit";

        return response()->json([
            'success' => true,
            'is_admin' => false,
            'max' => 2,
            'period' => '2 jam',
            'used' => $attempts,
            'remaining' => $remaining,
            'retry_after_seconds' => $seconds,
            'retry_after_minutes' => $minutes,
            'retry_in' => $seconds > 0 ? $timeString : null,
            'reasons' => [
                [
                    'title' => 'Mencegah Serangan Spam dan DDoS',
                    'desc' => 'Membatasi frekuensi kiriman (request) mencegah pihak tidak bertanggung jawab atau bot otomatis membanjiri server dengan ribuan laporan palsu dalam waktu singkat yang bisa membuat website menjadi lambat atau tumbang (down).'
                ],
                [
                    'title' => 'Efisiensi Validasi Tim Penyelidik',
                    'desc' => 'Menghindari penumpukan laporan duplikat atau berulang-ulang untuk masalah yang sama. Hal ini membantu tim admin atau investigator fokus memproses laporan yang benar-benar valid dan unik.'
                ],
                [
                    'title' => 'Optimalisasi Bandwidth dan Penyimpanan',
                    'desc' => 'Menghemat ruang penyimpanan basis data (database) server dari data sampah atau spam yang tidak perlu.'
                ],
                [
                    'title' => 'Mendorong Kualitas Laporan',
                    'desc' => 'Memastikan pengguna lebih berhati-hati, detail, dan serius saat menyusun isi laporan mereka karena adanya keterbatasan kuota pengiriman.'
                ],
            ]
        ]);
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


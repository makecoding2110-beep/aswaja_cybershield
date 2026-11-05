<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(protected MockDataRepository $repository) {}

    public function stats(): JsonResponse
    {
        $reports = $this->repository->getReports();
        $articles = $this->repository->getArticles();
        $cases = $this->repository->getCaseStudies();
        $activities = $this->repository->getActivities();

        $pendingCount = count(array_filter($reports, fn($r) => in_array($r['status'], ['Masuk', 'Pending'])));
        $processCount = count(array_filter($reports, fn($r) => $r['status'] === 'Diproses'));
        $resolvedCount = count(array_filter($reports, fn($r) => $r['status'] === 'Selesai'));

        return response()->json([
            'success' => true,
            'data' => [
                'totalArticles' => count($articles),
                'pendingReports' => $pendingCount,
                'processReports' => $processCount,
                'resolvedReports' => $resolvedCount,
                'totalCases' => count($cases),
                'recentReports' => array_slice($reports, 0, 5),
                'activities' => $activities
            ]
        ]);
    }
}


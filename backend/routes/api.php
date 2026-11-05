<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AiIntegrationController;
use App\Http\Controllers\Api\AnalyzeLinkController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Prinsip4TController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\AuthenticateApiToken;
use App\Http\Middleware\RequireSuperAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Aswaja CyberShield
|--------------------------------------------------------------------------
| RESTful API endpoints for Frontend HTML/CSS/JS Integration
| Prefix: /api
*/

// Health Check
Route::get('/ping', fn() => response()->json([
    'success' => true,
    'message' => 'Aswaja CyberShield REST API is online',
    'version' => '1.0.0',
    'timestamp' => now()->toIso8601String()
]));

// ==========================================
// 1. PUBLIC ENDPOINTS (Pengunjung)
// ==========================================

// AI Tabayyun Link Checker (Multi-Agent AI: Gemini + DeepSeek)
Route::post('/analyze-link', AnalyzeLinkController::class);

// Portal Lapor Siber Publik (Kirim Laporan)
Route::post('/reports', [ReportController::class, 'store']);

// Edukasi & Artikel Publik
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);

// Cyber Guard Kuis & Studi Kasus Publik
Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{id}', [CaseStudyController::class, 'show']);
Route::post('/cyber-guard/evaluate', [CaseStudyController::class, 'evaluateQuiz']);

// Kaidah 4T Fiqih Siber Publik
Route::get('/prinsip-4t', [Prinsip4TController::class, 'index']);

// ==========================================
// 2. OTENTIKASI ADMIN (Sanctum Compatible)
// ==========================================
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// 3. PROTECTED ADMIN ENDPOINTS
// ==========================================
Route::middleware([AuthenticateApiToken::class])->group(function () {

    // Profil Akun Aktif & Logout
    Route::get('/admin/me', [AuthController::class, 'me']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard KPI & Aktivitas
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Manajemen Laporan Aduan (Verifikasi & Investigasi)
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/{id}', [ReportController::class, 'show']);
    Route::put('/reports/{id}', [ReportController::class, 'update']);

    // Manajemen Artikel Edukasi (Create & Update)
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);

    // Manajemen Studi Kasus (Create & Update)
    Route::post('/case-studies', [CaseStudyController::class, 'store']);
    Route::put('/case-studies/{id}', [CaseStudyController::class, 'update']);

    // Manajemen Materi Kaidah 4T
    Route::put('/prinsip-4t/{id}', [Prinsip4TController::class, 'update']);

    // Daftar Akun Admin (Dapat dilihat oleh semua staf admin yang login)
    Route::get('/admins', [AdminUserController::class, 'index']);

    // ==========================================
    // 4. SUPER ADMIN ONLY (Hak Akses Penuh / RBAC)
    // ==========================================
    Route::middleware([RequireSuperAdmin::class])->group(function () {

        // Penghapusan Permanen Data
        Route::delete('/reports/{id}', [ReportController::class, 'destroy']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
        Route::delete('/case-studies/{id}', [CaseStudyController::class, 'destroy']);

        // Manajemen Akun Pengguna Staf Admin
        Route::post('/admins', [AdminUserController::class, 'store']);
        Route::put('/admins/{id}', [AdminUserController::class, 'update']);
        Route::delete('/admins/{id}', [AdminUserController::class, 'destroy']);

        // Monitoring & Pengujian Integrasi Dual AI (Gemini & DeepSeek)
        Route::get('/admin/ai/status', [AiIntegrationController::class, 'status']);
        Route::post('/admin/ai/test', [AiIntegrationController::class, 'testConnection']);
    });
});

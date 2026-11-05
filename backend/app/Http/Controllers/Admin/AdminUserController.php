<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MockDataRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function __construct(protected MockDataRepository $repository) {}

    public function index(): JsonResponse
    {
        $admins = $this->repository->getAdmins();
        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:Super Admin,Admin Basic',
            'status' => 'nullable|string|in:Aktif,Nonaktif'
        ]);

        $newAdmin = $this->repository->createAdmin($validated);

        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil didaftarkan.',
            'data' => $newAdmin
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'username' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string|in:Super Admin,Admin Basic',
            'status' => 'nullable|string|in:Aktif,Nonaktif'
        ]);

        $updated = $this->repository->updateAdmin($id, $validated);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Akun admin tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data akun admin berhasil diperbarui.',
            'data' => $updated
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        // Cegah menghapus akun Super Admin ID 1
        if ($id === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Super Admin utama tidak boleh dihapus demi keamanan sistem.'
            ], 403);
        }

        $deleted = $this->repository->deleteAdmin($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Akun admin tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil dihapus.'
        ]);
    }
}


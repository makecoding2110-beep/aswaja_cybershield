<?php

namespace App\Http\Middleware;

use App\Repositories\MockDataRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function __construct(protected MockDataRepository $repository) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token otorisasi diperlukan. Silakan login kembali.'
            ], 401);
        }

        // Cari user berdasarkan token
        $user = $this->repository->getUserByToken($token);

        // Dukungan untuk offline/development demo tokens
        if (!$user) {
            if ($token === 'demo_token' || str_starts_with($token, 'offline_admin_token_')) {
                $user = $this->repository->findUserByUsernameOrEmail('admin');
            }
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi telah berakhir atau token tidak valid. Silakan login kembali.'
            ], 401);
        }

        // Attach authenticated user to request
        $request->merge(['auth_user' => $user]);
        $request->setUserResolver(fn() => (object) $user);

        return $next($request);
    }
}


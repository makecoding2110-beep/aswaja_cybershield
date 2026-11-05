<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->get('auth_user') ?: (array) $request->user();

        $role = $user['role'] ?? '';
        if ($role !== 'Super Admin' && $role !== 'Superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Tindakan ini memerlukan hak akses Super Admin.'
            ], 403);
        }

        return $next($request);
    }
}


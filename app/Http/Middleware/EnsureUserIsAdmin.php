<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!in_array($user->user_type, ['super_admin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only super_admin or admin can access this resource.'
            ], 403);
        }

        return $next($request);
    }
}

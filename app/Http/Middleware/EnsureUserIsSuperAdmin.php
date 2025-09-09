<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!in_array($user->user_type, ['super_admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only super_admin can access this resource.'
            ], 403);
        }

        return $next($request);
    }
}

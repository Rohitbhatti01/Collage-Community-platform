<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (auth()->user()->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not approved yet. Please wait for admin approval.'
            ], 403);
        }

        return $next($request);
    }
}
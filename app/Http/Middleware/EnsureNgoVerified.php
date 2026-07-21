<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures that the authenticated NGO user has been verified/approved by an Admin.
 */
class EnsureNgoVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'ngo' && $user->verification_status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'F',
                    'timestamp' => now()->toIso8601String(),
                    'message' => 'Your NGO account has not been verified yet. Please wait for admin approval.',
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Your NGO account is pending verification.');
        }

        return $next($request);
    }
}

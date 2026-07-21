<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

/**
 * SECURITY: CSRF Protection (Module 3 - OWASP A5)
 * Enforces CSRF token validation on all state-changing form submissions.
 * All Blade forms must include @csrf directive.
 */
class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * API routes are excluded since they use token/key-based auth.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
    ];
}

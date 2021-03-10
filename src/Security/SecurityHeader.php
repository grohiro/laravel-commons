<?php
namespace LaravelCommons\Security;

/**
 * Add HTTP security headers
 */
class SecurityHeader
{
    public function __construct()
    {
    }

    public function handle($request, $next, $guard = null)
    {
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('X-Frame-Options', 'SAMEORIGIN', false);
            $response->header('X-XSS-Protection', 1, false);
            $response->header('X-Content-Type-Options', 'nosniff', false);
        }

        return $response;
    }
}

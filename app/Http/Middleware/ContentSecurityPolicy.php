<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response instanceof Response) {
            return $response;
        }

        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' https://cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline'; " .
            "img-src 'self' data:; " .
            "connect-src 'self'; " .
            "font-src 'self'; " .
            "frame-ancestors 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self'"
        );

        return $response;
    }
}

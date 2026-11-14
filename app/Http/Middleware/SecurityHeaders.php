<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** The same protective headers the old site sent, plus a content policy that still lets Tag Manager, YouTube, TikTok and Google Maps load. */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($request->is('admin*') || $request->is('livewire*')) return $response;
        $h = $response->headers;
        $h->set('X-Frame-Options', 'SAMEORIGIN');
        $h->set('X-Content-Type-Options', 'nosniff');
        $h->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $h->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');
        if ($request->isSecure() && config('site.hsts')) $h->set('Strict-Transport-Security', 'max-age=' . (int) config('site.hsts') . '; includeSubDomains');
        $h->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://www.tiktok.com https://*.tiktokcdn.com https://*.ttwstatic.com",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data:",
            "connect-src 'self' https://www.google-analytics.com https://*.google-analytics.com https://*.analytics.google.com https://www.googletagmanager.com https://*.tiktok.com",
            "frame-src https://www.youtube-nocookie.com https://www.youtube.com https://www.tiktok.com https://www.google.com https://www.googletagmanager.com",
            "media-src 'self' https:",
            "object-src 'none'", "base-uri 'self'", "form-action 'self'",
            "frame-ancestors 'self' https://core.shareinvestor.app https://posh.listedcompany.com",
        ]));
        return $response;
    }
}

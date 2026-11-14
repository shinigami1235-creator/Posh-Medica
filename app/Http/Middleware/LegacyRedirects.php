<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/** Redirects added in the admin (Redirects) are checked before a 404 is shown. */
class LegacyRedirects
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response->getStatusCode() !== 404) return $response;
        $map = Cache::rememberForever('redirects', fn () => Redirect::pluck('to_path', 'from_path')->all());
        $path = '/' . trim(rawurldecode($request->path()), '/');
        if (isset($map[$path])) {
            $code = Redirect::where('from_path', $path)->value('code') ?: 301;
            return redirect($map[$path], $code);
        }
        return $response;
    }
}

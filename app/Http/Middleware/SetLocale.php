<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/** Takes the language from the first part of the address (/th/... or /en/...). Thai is the default, like the old site. */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');
        app()->setLocale(in_array($locale, ['th', 'en'], true) ? $locale : 'th');
        URL::defaults(['locale' => app()->getLocale()]);
        $request->route()?->forgetParameter('locale');
        return $next($request);
    }
}

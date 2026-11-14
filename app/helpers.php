<?php

use App\Support\Texts;
use App\Support\Settings;

/* Small helpers the Blade views use. */

/** Page text for the current language, escaped by {{ }} in the view. Edit these under Page texts in the admin. */
function t(string $key): string
{
    return Texts::get($key);
}

/** Page text that holds a little HTML (bold, line breaks). Use with {!! !!}; it is cleaned when saved. */
function tx(string $key): string
{
    return Texts::get($key);
}

/** A file from the site theme, e.g. theme('img/hero/glass-glints.jpg'). */
function theme(string $path): string
{
    return asset('themes/default/assets/' . ltrim($path, '/'));
}

/** A named route in the current language. */
function lroute(string $name, array $params = []): string
{
    return route($name, array_merge(['locale' => app()->getLocale()], $params));
}

/** A path under the current language, e.g. lurl('contact-us'). */
function lurl(string $path = ''): string
{
    return url(app()->getLocale() . '/' . ltrim($path, '/'));
}

/** A site setting (phone, emails, Tag Manager ID ...). Edit these under Settings in the admin. */
function setting(string $key, ?string $default = null): ?string
{
    return Settings::get($key, $default);
}

/** A file uploaded in the admin, or one copied from the old site, stored under storage/app/public. */
function media(?string $path): ?string
{
    if (blank($path)) return null;
    if (preg_match('#^https?://#', $path)) return $path;
    return asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $path), '/'));
}

/** Thai dates use the Buddhist year and short Thai month names, like the old site (18 ส.ค. 2569). */
function site_date($date, ?string $locale = null): string
{
    $locale ??= app()->getLocale();
    $d = \Illuminate\Support\Carbon::parse($date);
    if ($locale === 'th') {
        $m = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'][$d->month - 1];
        return $d->format('d') . ' ' . $m . ' ' . ($d->year + 543);
    }
    return $d->format('d M Y');
}

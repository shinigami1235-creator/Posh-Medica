<?php

namespace App\Support;

/** What each page tells search engines and social apps: title, description, picture, the same page in the other language, and structured data. */
class Seo
{
    public static function make(array $o = []): array
    {
        $l = app()->getLocale();
        $site = Texts::get('meta.site-name');
        $title = $o['title'] ?? $site;
        return [
            'title' => $title === $site ? $site : $title . ' | ' . $site,
            'description' => str($o['description'] ?? Texts::get('meta.home-description'))->squish()->limit(160, '…')->toString(),
            'image' => $o['image'] ?? theme('img/og-image.jpg'),
            'type' => $o['type'] ?? 'website',
            'canonical' => $o['canonical'] ?? url()->current(),
            'alternates' => $o['alternates'] ?? self::alternates(),
            'schema' => $o['schema'] ?? [],
            'crumbs' => $o['crumbs'] ?? [],
            'noindex' => $o['noindex'] ?? false,
            'locale' => $l,
        ];
    }

    /** The same address with the language swapped, for pages whose address is the same in both languages. */
    public static function alternates(): array
    {
        $path = request()->path();
        $rest = preg_replace('#^(th|en)(/|$)#', '', $path);
        return ['th' => url('th/' . $rest), 'en' => url('en/' . $rest)];
    }

    public static function breadcrumbs(array $crumbs): array
    {
        return ['@type' => 'BreadcrumbList', 'itemListElement' => collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
            '@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $c[1] ?? null,
        ]))->all()];
    }
}

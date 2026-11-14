<?php

namespace App\Http\Controllers;

use App\Models\{Award, Milestone, Post, Product, Stat, Store, Video};
use App\Support\Seo;

class PageController extends Controller
{
    public function home()
    {
        $products = Product::published()->orderBy('sort')->get();
        return view('pages.home', [
            'seo' => Seo::make(['title' => t('meta.site-name'), 'description' => t('meta.home-description'), 'schema' => [[
                '@type' => 'WebSite', 'name' => t('meta.site-name'), 'url' => url(app()->getLocale() . '/home'), 'inLanguage' => app()->getLocale(),
            ]]]),
            'products' => $products,
            'stats' => Stat::orderBy('sort')->get(),
            'videos' => Video::where('is_published', true)->orderBy('sort')->get(),
            'stores' => Store::where('is_published', true)->orderBy('shelf')->orderBy('sort')->get(),
            'latest' => Post::published()->where('kind', 'event')->latest('published_on')->limit(3)->get(),
            'css' => ['hero', 'table', 'home'], 'js' => ['hero', 'table', 'tiktok', 'gauges', 'tv'],
            // the periodic table and the hero tag read these
            'productsJs' => $products->map(fn ($p) => ['id' => $p->id, 'name' => $p->l('name'), 'group' => $p->group, 'form' => $p->form, 'sym' => $p->symbol,
                'img' => media($p->image), 'desc' => $p->l('desc'), 'ingr' => $p->l('ingredients') ?? [], 'badges' => array_map(fn ($b) => __('posh.badges.' . $b) === 'posh.badges.' . $b ? $b : __('posh.badges.' . $b), $p->badges ?? [])])->values(),
            'productUrls' => $products->mapWithKeys(fn ($p) => [$p->id => $p->url()]),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'seo' => Seo::make(['title' => t('meta.about-title'), 'description' => t('meta.about-description'),
                'schema' => [Seo::breadcrumbs([[t('nav.home'), lroute('home')], [t('nav.about-us')]])]]),
            'stats' => Stat::orderBy('sort')->get(),
            'milestones' => Milestone::where('is_published', true)->orderBy('sort')->get(),
            'accent' => ['#f05a28', '#ffe1d2', '#a8371a'], 'js' => ['about'],
        ]);
    }

    public function awards()
    {
        return view('pages.awards', [
            'seo' => Seo::make(['title' => t('meta.awards-title'), 'description' => t('meta.awards-description'),
                'schema' => [Seo::breadcrumbs([[t('nav.home'), lroute('home')], [t('nav.about-us'), lroute('about')], [t('nav.awards-standards')]])]]),
            'awards' => Award::where('is_published', true)->orderBy('sort')->get()->groupBy('kind'),
            'accent' => ['#e0a21b', '#fff1c9', '#8a5a00'],
        ]);
    }

    public function oem()
    {
        return view('pages.oem', [
            'seo' => Seo::make(['title' => t('meta.oem-title'), 'description' => t('meta.oem-description'),
                'schema' => [Seo::breadcrumbs([[t('nav.home'), lroute('home')], [t('nav.our-business')], [t('nav.oem-and-odm')]])]]),
            'accent' => ['#14967f', '#d2f2e9', '#0b5f50'], 'js' => ['belt'],
        ]);
    }

    /** Old addresses for parts of a page that is now one page: send them to the right spot on it. */
    public static function to(string $route, string $hash = '')
    {
        return fn () => redirect(lroute($route) . $hash, 301);
    }
}

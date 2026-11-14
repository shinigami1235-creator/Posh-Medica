<?php

namespace App\Http\Controllers;

use App\Models\{LegalPage, Policy, Post, Product};

/** sitemap.xml lists every page in Thai and English, each pointing at its other language, and robots.txt points search engines to it. */
class SitemapController extends Controller
{
    public function xml()
    {
        $pages = [];
        $both = function ($path, $lastmod = null) use (&$pages) { $pages[] = ['th' => url('th/' . $path), 'en' => url('en/' . $path), 'lastmod' => $lastmod]; };
        foreach (['home', 'about-us/corporate-info', 'about-us/awards-standards/awards', 'our-business/services/oem-and-odm', 'updates/blog-and-event/event', 'updates/blog-and-event/blog', 'contact-us', 'sitemap'] as $p) $both($p);
        foreach (Policy::orderBy('sort')->get() as $p) $both('investor-relations/corporate-governance/' . $p->slug, $p->updated_at);
        foreach (LegalPage::all() as $p) $both($p->slug, $p->updated_at);
        foreach (Product::published()->get() as $p) $pages[] = ['th' => $p->url('th'), 'en' => $p->url('en'), 'lastmod' => $p->updated_at];
        foreach (Post::published()->get() as $p) $pages[] = ['th' => $p->url('th'), 'en' => $p->url('en'), 'lastmod' => $p->updated_at];
        return response()->view('seo.sitemap', ['pages' => $pages])->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots()
    {
        $lines = config('site.noindex')
            ? ['User-agent: *', 'Disallow: /']
            : ['User-agent: *', 'Disallow: /admin', 'Disallow: /livewire', 'Disallow: /api', '', 'Sitemap: ' . url('sitemap.xml')];
        return response(implode("\n", $lines) . "\n")->header('Content-Type', 'text/plain');
    }
}

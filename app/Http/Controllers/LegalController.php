<?php

namespace App\Http\Controllers;

use App\Models\{LegalPage, Policy, Post, Product};
use App\Support\Seo;

class LegalController extends Controller
{
    public function show(string $page)
    {
        $p = LegalPage::where('slug', $page)->firstOrFail();
        return view('pages.legal', ['p' => $p, 'seo' => Seo::make(['title' => $p->l('title'), 'description' => strip_tags($p->l('body'))]), 'accent' => ['#26235e', '#e6e5f3', '#26235e']]);
    }

    /** The sitemap page people can read (sitemap.xml is the one for search engines). */
    public function sitemap()
    {
        return view('pages.sitemap', [
            'products' => Product::published()->orderBy('sort')->get(), 'policies' => Policy::orderBy('sort')->get(),
            'legal' => LegalPage::all(), 'seo' => Seo::make(['title' => t('nav.sitemap')]), 'accent' => ['#26235e', '#e6e5f3', '#26235e'],
        ]);
    }
}

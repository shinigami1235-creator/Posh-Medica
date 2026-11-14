<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Support\Seo;

class PolicyController extends Controller
{
    public function show(string $policy)
    {
        $p = Policy::where('slug', $policy)->firstOrFail();
        return view('pages.policy', [
            'p' => $p, 'all' => Policy::orderBy('sort')->get(),
            'seo' => Seo::make(['title' => $p->l('title') . ' | ' . t('nav.investor-relations'), 'description' => strip_tags($p->l('body')),
                'schema' => [Seo::breadcrumbs([[t('nav.home'), lroute('home')], [t('nav.investor-relations')], [$p->l('title')]])]]),
            'accent' => ['#26235e', '#e6e5f3', '#26235e'],
        ]);
    }
}

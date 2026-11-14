<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Seo;

class ProductController extends Controller
{
    public function show(int $id, ?string $slug = null)
    {
        $p = Product::published()->findOrFail($id);
        $l = app()->getLocale();
        if ($slug === null || rawurldecode($slug) !== $p->{'slug_' . $l}) return redirect($p->url(), 301);   // like the old site, /products/supplements/2 goes to the full address
        $group = __('posh.groups.' . $p->group);
        $photos = array_values(array_filter(array_merge([$p->image], $p->photos ?? [])));
        return view('pages.product', [
            'p' => $p, 'photos' => $photos,
            'more' => Product::published()->where('group', $p->group)->whereKeyNot($p->id)->orderBy('sort')->get(),
            'seo' => Seo::make([
                'title' => $p->l('seo_title') ?: $p->l('name') . ' | ' . __('posh.supplements'),
                'description' => $p->l('seo_description') ?: $p->l('desc'),
                'image' => media($photos[0] ?? null), 'type' => 'product',
                'alternates' => ['th' => $p->url('th'), 'en' => $p->url('en')], 'canonical' => $p->url(),
                'schema' => [
                    ['@type' => 'Product', 'name' => $p->l('name'), 'description' => $p->l('desc'), 'image' => array_map('media', $photos),
                     'brand' => ['@type' => 'Brand', 'name' => 'Posh'], 'category' => $group, 'sku' => 'POSH-' . str_pad($p->id, 2, '0', STR_PAD_LEFT)],
                    Seo::breadcrumbs([[t('nav.home'), lroute('home')], [__('posh.supplements'), lroute('home') . '#products'], [$p->l('name')]]),
                ],
            ]),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\Seo;

class PostController extends Controller
{
    public function index(string $kind)
    {
        $posts = Post::published()->where('kind', $kind)->latest('published_on')->paginate(12)->withQueryString();
        $name = __('posh.kinds.' . $kind);
        return view('pages.posts', [
            'kind' => $kind, 'posts' => $posts,
            'seo' => Seo::make(['title' => $name . ' | ' . t('nav.blog-and-event'), 'description' => t('meta.events-description'),
                'canonical' => $posts->currentPage() > 1 ? $posts->url($posts->currentPage()) : url()->current(),
                'schema' => [Seo::breadcrumbs([[t('nav.home'), lroute('home')], [t('nav.blog-and-event'), lroute('events')], [$name]])]]),
            'accent' => ['#e03f78', '#ffdbe7', '#a01e50'],
        ]);
    }

    public function show(string $kind, int $id, ?string $slug = null)
    {
        $post = Post::published()->where('kind', $kind)->findOrFail($id);
        if ($slug === null || rawurldecode($slug) !== $post->slug) return redirect($post->url(), 301);
        $images = array_values(array_filter(array_merge([$post->cover], $post->gallery ?? [])));
        $text = trim(strip_tags($post->l('body')));
        return view('pages.post', [
            'post' => $post, 'images' => $post->gallery ?? [],
            'others' => Post::published()->where('kind', $kind)->whereKeyNot($id)->latest('published_on')->limit(3)->get(),
            'seo' => Seo::make([
                'title' => $post->l('title'), 'description' => $post->l('seo_description') ?: $text, 'type' => 'article',
                'image' => media($images[0] ?? null), 'canonical' => $post->url(),
                'schema' => [
                    ['@type' => $kind === 'blog' ? 'BlogPosting' : 'NewsArticle', 'headline' => str($post->l('title'))->limit(110)->toString(),
                     'datePublished' => $post->published_on->toDateString(), 'dateModified' => $post->updated_at?->toAtomString(),
                     'image' => array_map('media', array_slice($images, 0, 3)), 'inLanguage' => app()->getLocale(),
                     'author' => ['@type' => 'Organization', 'name' => t('meta.site-name')],
                     'publisher' => ['@type' => 'Organization', 'name' => t('meta.site-name'), 'logo' => ['@type' => 'ImageObject', 'url' => theme('img/posh-logo.png')]],
                     'mainEntityOfPage' => $post->url()],
                    Seo::breadcrumbs([[t('nav.home'), lroute('home')], [t('nav.blog-and-event'), lroute('events')], [__('posh.kinds.' . $kind), lroute($kind === 'blog' ? 'blog' : 'events')], [$post->l('title')]]),
                ],
            ]),
            'accent' => ['#e03f78', '#ffdbe7', '#a01e50'],
        ]);
    }
}

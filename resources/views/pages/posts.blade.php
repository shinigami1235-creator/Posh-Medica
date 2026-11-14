@extends('layouts.site')
@section('content')
<section class="phead">
  <p class="phead__giant" aria-hidden="true">{{ t('events.news') }}</p><img class="phead__deco" src="{{ theme('img/hero/strawberry-splash.webp') }}" alt="" aria-hidden="true">
  <div class="phead__in">
    <nav class="crumbs" aria-label="{{ t('nav.breadcrumb') }}"><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a> <span aria-hidden="true">/</span> <span>{{ t('nav.blog-and-event') }}</span></nav>
    <h1>{{ t('nav.blog-and-event') }}</h1>
  </div>
</section>
<section class="sec"><div class="wrap">
  <nav class="tabs" aria-label="{{ t('nav.blog-and-event') }}">
    <a href="{{ lroute('events') }}" @if ($kind === 'event') aria-current="page" @endif>{{ __('posh.kinds.event') }}</a>
    <a href="{{ lroute('blog') }}" @if ($kind === 'blog') aria-current="page" @endif>{{ __('posh.kinds.blog') }}</a>
  </nav>
  <ul class="posts">
  @foreach ($posts as $post)
    <li class="post"><a href="{{ $post->url() }}"><div class="post__img"><img src="{{ media($post->cover) }}" alt="" width="600" height="440" loading="lazy" decoding="async"><span class="post__date">{{ site_date($post->published_on) }}</span></div><h3>{{ $post->l('title') }}</h3><span class="post__more">{{ __('posh.read_more') }}</span></a></li>
  @endforeach
  </ul>
  {{ $posts->onEachSide(1)->links('partials.pager') }}
</div></section>
@endsection

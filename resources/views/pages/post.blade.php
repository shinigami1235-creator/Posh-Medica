@extends('layouts.site')
@section('content')
<article class="article">
  <header class="article__head wrap narrow">
    <nav class="crumbs" aria-label="{{ t('nav.breadcrumb') }}"><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a> <span aria-hidden="true">/</span> <a href="{{ lroute($post->kind === 'blog' ? 'blog' : 'events') }}">{{ __('posh.kinds.' . $post->kind) }}</a></nav>
    <p class="article__date"><time datetime="{{ $post->published_on->toDateString() }}">{{ site_date($post->published_on) }}</time></p>
    <h1>{{ $post->l('title') }}</h1>
  </header>
  @if ($images)
  <div class="article__gallery" data-gallery>
    <figure class="article__main"><img src="{{ media($images[0]) }}" alt="{{ $post->l('title') }}" width="1200" height="800"></figure>
    @if (count($images) > 1)
    <ul class="article__thumbs">@foreach ($images as $i => $im)<li><button type="button" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" data-src="{{ media($im) }}" aria-label="{{ __('posh.photo') }} {{ $i + 1 }}"><img src="{{ media($im) }}" alt="" loading="lazy"></button></li>@endforeach</ul>
    @endif
  </div>
  @endif
  <div class="article__body wrap narrow">{!! \App\Support\Html::clean($post->l('body')) !!}</div>
  <p class="wrap narrow"><a class="btn" href="{{ lroute($post->kind === 'blog' ? 'blog' : 'events') }}">{{ __('posh.back') }}</a></p>
</article>
@if ($others->isNotEmpty())
<section class="sec sec--tint"><div class="wrap"><h2 class="h2">{{ __('posh.more_posts') }}</h2>
<ul class="posts">@foreach ($others as $o)<li class="post"><a href="{{ $o->url() }}"><div class="post__img"><img src="{{ media($o->cover) }}" alt="" width="600" height="440" loading="lazy"><span class="post__date">{{ site_date($o->published_on) }}</span></div><h3>{{ $o->l('title') }}</h3><span class="post__more">{{ __('posh.read_more') }}</span></a></li>@endforeach</ul>
</div></section>
@endif
@endsection
@push('scripts')
<script>
document.querySelectorAll('.article__thumbs button').forEach(b => b.addEventListener('click', () => {
  document.querySelector('.article__main img').src = b.dataset.src;
  document.querySelectorAll('.article__thumbs button').forEach(x => x.setAttribute('aria-pressed', x === b));
}));
</script>
@endpush

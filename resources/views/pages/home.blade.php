@extends('layouts.site')
@section('content')
<section aria-label="{{ t('home.toward-medical-excellence') }}" class="hero" id="hero">
<div class="hero__stage">
<div aria-hidden="true" class="hero__grid"></div>
<h1 class="hero__type">{!! tx('home.towardmedicalexcellence') !!}</h1>
<div aria-hidden="true" class="bench">
<img alt="" class="bench__splash" src="{{ theme('img/hero/tamarind-splash.webp') }}"/>
<div class="glass">
<img alt="" class="glass__tint" data-small="{{ theme('img/hero/glass-water-tint-s.jpg') }}" src="{{ theme('img/hero/glass-water-tint.jpg') }}"/>
<img alt="" class="glass__shine" data-small="{{ theme('img/hero/glass-water-shine-s.jpg') }}" src="{{ theme('img/hero/glass-water-shine.jpg') }}"/>
<img alt="" class="glass__drink" src="{{ theme('img/hero/glass-tamarind-drink.webp') }}"/>
<img alt="" class="glass__gloss" data-small="{{ theme('img/hero/glass-glints-s.jpg') }}" src="{{ theme('img/hero/glass-glints.jpg') }}"/>
</div>
<div class="sachet"><img alt="" class="sachet__body" src="{{ theme('img/hero/tamarind-sachet.webp') }}"/><img alt="" class="sachet__strip" src="{{ theme('img/hero/tamarind-sachet.webp') }}"/></div>
</div>
<canvas aria-hidden="true" class="hero__fx"></canvas>
<div aria-live="polite" class="hero__copy"><p class="hero__text">{{ t('home.we-are-committed-to-excellence-in') }}</p><p class="hero__text">{{ t('home.we-innovate-with-expertise-to-deliver') }}</p><p class="hero__text">{{ t('home.rooted-in-ethics-and-transparency-we') }}</p></div>
<div class="flavours"><p class="flavours__label" id="flavLabel">{{ t('home.pick-a-flavour') }}</p><ul aria-labelledby="flavLabel" role="radiogroup"><li><button aria-checked="true" role="radio" tabindex="0"><img alt="" height="58" src="{{ theme('img/hero/thumb-tamarind.webp') }}" width="40"/><span>{{ t('home.tamarind') }}</span></button></li><li><button aria-checked="false" role="radio" tabindex="-1"><img alt="" height="58" src="{{ theme('img/hero/thumb-tangerine.webp') }}" width="40"/><span>{{ t('home.tangerine') }}</span></button></li><li><button aria-checked="false" role="radio" tabindex="-1"><img alt="" height="58" src="{{ theme('img/hero/thumb-lime.webp') }}" width="40"/><span>{{ t('home.lime') }}</span></button></li><li><button aria-checked="false" role="radio" tabindex="-1"><img alt="" height="58" src="{{ theme('img/hero/thumb-strawberry.webp') }}" width="40"/><span>{{ t('home.strawberry') }}</span></button></li><li><button aria-checked="false" role="radio" tabindex="-1"><img alt="" height="58" src="{{ theme('img/hero/thumb-lychee.webp') }}" width="40"/><span>{{ t('home.collagen-lychee') }}</span></button></li></ul></div>
<a class="tag" href="product.html?id=2">
<span class="tag__no">{!! tx('home.no-02') !!}</span><span class="tag__group">{{ t('home.fiber-powder') }}</span>
<span class="tag__name">{{ t('home.tamarind-fiber') }}</span>
<span class="tag__go">{{ t('home.more-detail') }} <svg aria-hidden="true" height="16" viewbox="0 0 16 16" width="16"><path d="M3 8h9M8.5 4l4 4-4 4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></span>
</a>
</div>
</section><section aria-labelledby="tableTitle" class="table" id="products">
@include('partials.home-table')
<dialog aria-labelledby="lcName" class="labcard">
<button aria-label="{{ t('home.close') }}" class="labcard__x">×</button>
<div class="labcard__media"><img alt=""/></div>
<div class="labcard__body">
<p class="labcard__meta"><span class="labcard__no"></span><span class="labcard__group"></span></p>
<h3 class="labcard__name" id="lcName"></h3>
<h4>{{ t('home.descriptions') }}</h4>
<p class="labcard__desc"></p>
<h4>{{ t('home.ingredients') }}</h4>
<ul class="labcard__formula"></ul>
<ul class="labcard__badges"></ul>
<a class="labcard__go" href="#">{{ t('home.more-detail-2') }}</a>
</div>
</dialog>
</section>
<section class="band band--orange" id="about">
<p aria-hidden="true" class="band__giant">{{ t('home.15-years') }}</p>
<div class="wrap split">
<div>
<h2 class="h2">{{ t('home.about-us') }}</h2>
<p class="band__sub">{!! tx('home.posh-medica-lifetechpublic-company-limited') !!}</p>
<p class="lead">{!! tx('home.for-over-15-years-posh-has') !!}</p>
<a class="btn btn--light" href="{{ lroute('about') }}">{{ t('home.read-more') }}</a>
</div>
<div aria-hidden="true" class="floats"><img alt="" class="float f0" decoding="async" height="268" loading="lazy" src="{{ theme('img/home/honey-lime.png') }}" width="266"/><img alt="" class="float f1" decoding="async" height="210" loading="lazy" src="{{ theme('img/home/lychee.png') }}" width="198"/><img alt="" class="float f2" decoding="async" height="172" loading="lazy" src="{{ theme('img/home/tamarind.png') }}" width="224"/><img alt="" class="float f3" decoding="async" height="199" loading="lazy" src="{{ theme('img/home/orange.png') }}" width="224"/><img alt="" class="float f4" decoding="async" height="274" loading="lazy" src="{{ theme('img/home/lime-fiber.png') }}" width="286"/><img alt="" class="float f5" decoding="async" height="294" loading="lazy" src="{{ theme('img/home/lychee-collagen.png') }}" width="255"/><img alt="" class="float f6" decoding="async" height="340" loading="lazy" src="{{ theme('img/home/tamarind-fiber.png') }}" width="270"/><img alt="" class="float f7" decoding="async" height="303" loading="lazy" src="{{ theme('img/home/orange-fiber.png') }}" width="217"/></div>
</div>
<svg aria-hidden="true" height="0" style="position:absolute" width="0"><defs>
<radialgradient id="bub"><stop offset=".55" stop-color="#fff" stop-opacity=".05"></stop><stop offset=".85" stop-color="#fff" stop-opacity=".55"></stop><stop offset="1" stop-color="#fff" stop-opacity=".9"></stop></radialgradient>
<lineargradient id="glass" x1="0" x2="1"><stop offset="0" stop-color="#dfe3ee"></stop><stop offset=".35" stop-color="#fbfbff"></stop><stop offset=".75" stop-color="#f1f2f7"></stop><stop offset="1" stop-color="#d4d8e6"></stop></lineargradient>
<lineargradient id="deep" x1="0" x2="0" y1="0" y2="1"><stop offset=".1" stop-color="#000" stop-opacity="0"></stop><stop offset=".85" stop-color="#000" stop-opacity=".22"></stop></lineargradient></defs></svg>@include('partials.home-stats')
</section>
<section class="band band--tiktok" id="feed">
<p aria-hidden="true" class="band__giant band__giant--tt">{{ t('home.posh-aroi') }}</p>
<div class="wrap feed">
<div class="feed__side">
<p class="feed__handle">{!! tx('home.tiktok-posh-aroi') !!}</p>
<h2 class="h2">{{ t('home.posh-medica') }}</h2>
<p lang="th">บริษัท พอช เมดิก้า ไลฟ์เทค จำกัด (มหาชน)<br/>ผลิตและจำหน่าย อาหารเสริม,เครื่องสำอาง</p>
<a class="btn btn--tt" href="https://www.tiktok.com/@posh_aroi" rel="noopener" target="_blank">{{ t('home.follow-on-tiktok') }}</a>
</div>
<div class="feed__stage">
<img alt="" aria-hidden="true" class="feed__sticker feed__sticker--a" src="{{ theme('img/hero/strawberry-sachet.webp') }}"/>
<img alt="" aria-hidden="true" class="feed__sticker feed__sticker--b" src="{{ theme('img/hero/tamarind-sachet.webp') }}"/>
<div class="feed__screen">
<blockquote cite="https://www.tiktok.com/@posh_aroi" class="tiktok-embed" data-embed-type="creator" data-unique-id="posh_aroi" style="max-width:780px;min-width:288px;margin:0">
<section><a href="https://www.tiktok.com/@posh_aroi?refer=creator_embed" target="_blank">{{ t('home.posh-aroi-2') }}</a></section>
</blockquote>
</div>
</div>
</div>
</section>
<section class="band band--yt" id="videos">
<p aria-hidden="true" class="band__giant">{{ t('home.on-air') }}</p>
<div class="wrap yt">
<div class="yt__side">
<p class="feed__handle yt__handle">{!! tx('home.youtube-poshmedicathailand700') !!}</p>
<h2 class="h2">{{ t('home.posh-medica-2') }}</h2>
<a class="btn yt__btn" href="https://www.youtube.com/@poshmedicathailand700" rel="noopener" target="_blank">{{ t('home.open-the-channel') }}</a>
</div>
@include('partials.home-videos')
</div>
</section>
<section class="band band--store" id="where">
<div class="wrap"><h2 class="h2 center">{{ t('home.distribution-channels-partners') }}</h2>@include('partials.home-stores')</div>
</section>
<section class="band band--gold" id="awards">
<div class="wrap"><h2 class="h2 center">{{ t('home.award-standard') }}</h2>
<div class="podiums">
<a class="podium" href="{{ lroute('awards') }}#awards"><span aria-hidden="true" class="podium__spot"></span><img alt="{{ t('home.awards') }}" decoding="async" height="277" loading="lazy" src="{{ theme('img/home/trophy.webp') }}" width="434"/><span class="podium__block">{!! tx('home.awardsview-more') !!}</span></a>
<a class="podium podium--b" href="{{ lroute('awards') }}#standards"><span aria-hidden="true" class="podium__spot"></span><img alt="{{ t('home.standards') }}" decoding="async" height="239" loading="lazy" src="{{ theme('img/home/certificates.webp') }}" width="434"/><span class="podium__block">{!! tx('home.standardsview-more') !!}</span></a>
</div></div>
</section>
<section class="band band--pink" id="events">
<div class="wrap"><h2 class="h2">{{ t('home.latest-event') }}</h2>@include('partials.home-posts')
<p class="center" style="margin-top:40px"><a class="btn" href="{{ lroute('events') }}">{{ t('home.view-more') }}</a></p></div>
</section>
@endsection

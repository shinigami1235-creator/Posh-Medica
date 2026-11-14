@extends('layouts.site')
@section('content')
<section class="phead"><div class="phead__in"><h1>{{ t('nav.sitemap') }}</h1></div></section>
<section class="sec"><div class="wrap sitemap">
  <div><h2 class="h3"><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a></h2></div>
  <div><h2 class="h3">{{ t('nav.about-us') }}</h2><ul><li><a href="{{ lroute('about') }}">{{ t('nav.corporate-info') }}</a></li><li><a href="{{ lroute('about') }}#milestone">{{ t('nav.company-milestone') }}</a></li><li><a href="{{ lroute('about') }}#message">{{ t('nav.message-from-company') }}</a></li><li><a href="{{ lroute('about') }}#org">{{ t('nav.organization-chart') }}</a></li><li><a href="{{ lroute('awards') }}">{{ t('nav.awards-standards') }}</a></li></ul></div>
  <div><h2 class="h3">{{ t('nav.our-business') }}</h2><ul><li><a href="{{ lroute('oem') }}">{{ t('nav.oem-and-odm') }}</a></li>@foreach ($products as $p)<li><a href="{{ $p->url() }}">{{ $p->l('name') }}</a></li>@endforeach</ul></div>
  <div><h2 class="h3">{{ t('nav.investor-relations') }}</h2><ul>@foreach ($policies as $p)<li><a href="{{ lroute('policy', ['policy' => $p->slug]) }}">{{ $p->l('title') }}</a></li>@endforeach</ul></div>
  <div><h2 class="h3">{{ t('nav.blog-and-event') }}</h2><ul><li><a href="{{ lroute('events') }}">{{ __('posh.kinds.event') }}</a></li><li><a href="{{ lroute('blog') }}">{{ __('posh.kinds.blog') }}</a></li></ul></div>
  <div><h2 class="h3"><a href="{{ lroute('contact') }}">{{ t('nav.contact-us') }}</a></h2><ul>@foreach ($legal as $p)<li><a href="{{ lroute('legal', ['page' => $p->slug]) }}">{{ $p->l('title') }}</a></li>@endforeach</ul></div>
</div></section>
@endsection

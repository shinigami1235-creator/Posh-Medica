@extends('layouts.site')
@section('content')
<section class="phead">
<p aria-hidden="true" class="phead__giant">{{ t('awards.trophy') }}</p><img alt="" aria-hidden="true" class="phead__deco" src="{{ theme('img/hero/lime-splash.webp') }}"/>
<div class="phead__in">
<nav aria-label="{{ t('awards.breadcrumb') }}" class="crumbs"><a href="{{ lroute('home') }}">{{ t('awards.home') }}</a> <span aria-hidden="true">/</span> <a href="{{ lroute('about') }}">{{ t('awards.about-us') }}</a> <span aria-hidden="true">/</span> <span>{{ t('awards.awards-standards') }}</span></nav>
<h1>{{ t('awards.accreditation-recognition') }}</h1>
</div>
</section>
<section class="sec"><div class="wrap narrow"><p>{!! tx('awards.at-posh-medica-lifetech-public-company') !!}</p><p>{!! tx('awards.in-2016-the-company-received-the') !!}</p></div></section>
<section class="sec sec--tint" id="awards"><div class="wrap"><h2 class="h2">{{ t('awards.awards') }}</h2>@include('partials.awards-wall', ['kind' => 'award'])</div></section>
<section class="sec" id="standards"><div class="wrap"><h2 class="h2">{{ t('awards.standards') }}</h2>@include('partials.awards-wall', ['kind' => 'standard'])</div></section>
@endsection

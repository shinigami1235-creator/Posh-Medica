@extends('layouts.site')
@section('content')
<section class="phead">
<p aria-hidden="true" class="phead__giant">{{ t('contact.hello') }}</p><img alt="" aria-hidden="true" class="phead__deco" src="{{ theme('img/hero/lychee-splash.webp') }}"/>
<div class="phead__in">
<nav aria-label="{{ t('contact.breadcrumb') }}" class="crumbs"><a href="{{ lroute('home') }}">{{ t('contact.home') }}</a> <span aria-hidden="true">/</span> <span>{{ t('contact.contact-us') }}</span></nav>
<h1>{{ t('contact.contact-us-2') }}</h1>
</div>
</section>
<section class="sec"><div class="wrap contact">
<div class="contact__info">
<figure class="cutframe"><img alt="{{ t('contact.posh-medica-lifetech-building') }}" decoding="async" height="291" loading="lazy" src="{{ theme('img/contact/building.webp') }}" width="436"/></figure>
<h2 class="h3">{{ t('contact.posh-medica-lifetech-public-company-limited') }}</h2>
<p>{{ t('contact.27-8-soi-01-kanchanaphisek-11') }}</p>
<p>{{ t('contact.for-customer') }} <a href="mailto:customer@poshmedica.com">{{ t('contact.customer-poshmedica-com') }}</a><br/>{{ t('contact.for-shareholder-and-investor') }} <a href="mailto:companysecretary@poshmedica.com">{{ t('contact.companysecretary-poshmedica-com') }}</a></p>
<p class="contact__call"><a class="btn" href="tel:020977700">02 097 7700</a> <a class="btn btn--line" href="https://page.line.me/rng1382b" rel="noopener" target="_blank">{{ t('contact.line') }}</a></p>
<iframe class="map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Posh+Medica+Lifetech+27/8+Soi+01+Kanchanaphisek+11/1+Khan+Na+Yao+Bangkok&amp;output=embed" title="{{ t('contact.map-to-posh-medica-lifetech') }}"></iframe>
</div>
@include('partials.contact-form')
</div></section>
@endsection

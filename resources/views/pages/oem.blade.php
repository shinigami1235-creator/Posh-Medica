@extends('layouts.site')
@section('content')
<section class="phead">
<p aria-hidden="true" class="phead__giant">{{ t('oem.factory') }}</p><img alt="" aria-hidden="true" class="phead__deco" src="{{ theme('img/hero/tangerine-splash.webp') }}"/>
<div class="phead__in">
<nav aria-label="{{ t('oem.breadcrumb') }}" class="crumbs"><a href="{{ lroute('home') }}">{{ t('oem.home') }}</a> <span aria-hidden="true">/</span> <span>{{ t('oem.our-business') }}</span> <span aria-hidden="true">/</span> <span>{{ t('oem.oem-and-odm') }}</span></nav>
<h1>{{ t('oem.oem-and-odm-2') }}</h1>
</div>
</section>
<section class="sec">
<div class="wrap split">
<div class="split__text"><p class="lead">{!! tx('oem.posh-medica-lifetech-is-your-trusted') !!}</p>
<a class="btn" href="contact.html?topic=info">{{ t('oem.contact-us') }}</a></div>
<figure class="cutframe"><img alt="" decoding="async" height="415" loading="lazy" src="{{ theme('img/oem/packing.webp') }}" width="900"/></figure>
</div>
</section>
<section class="sec sec--tint">
<div class="wrap"><h2 class="h2">{{ t('oem.what-we-deliver') }}</h2></div>
<div class="belt"><ol class="belt__track"><li class="station"><div class="station__icon"><img alt="" decoding="async" height="62" loading="lazy" src="{{ theme('img/oem/cosmetic-fill.png') }}" width="62"/></div><h3>{{ t('oem.initial-consultation') }}</h3><p>{{ t('oem.we-provide-business-planning-and-formula') }}</p></li><li class="station"><div class="station__icon"><img alt="" decoding="async" height="62" loading="lazy" src="{{ theme('img/oem/product-label.png') }}" width="62"/></div><h3>{{ t('oem.customized-formula-development-odm') }}</h3><p>{{ t('oem.formulated-by-a-team-of-doctors') }}</p></li><li class="station"><div class="station__icon"><img alt="" decoding="async" height="62" loading="lazy" src="{{ theme('img/oem/capsule.png') }}" width="62"/></div><h3>{{ t('oem.manufacturing-under-your-formula-oem') }}</h3><p>{{ t('oem.produced-with-premium-grade-ingredients-under') }}</p></li><li class="station"><div class="station__icon"><img alt="" decoding="async" height="62" loading="lazy" src="{{ theme('img/oem/packing.png') }}" width="62"/></div><h3>{{ t('oem.packaging-design-logo-and-brand-ci') }}</h3><p>{{ t('oem.designed-by-our-experienced-and-professional') }}</p></li><li class="station"><div class="station__icon"><img alt="" decoding="async" height="62" loading="lazy" src="{{ theme('img/oem/lot-expired-print.png') }}" width="62"/></div><h3>{{ t('oem.fda-registration-and-legal-documentation-service') }}</h3><p>{{ t('oem.we-take-care-of-all-required') }}</p></li><li class="station"><div class="station__icon"><img alt="" decoding="async" height="62" loading="lazy" src="{{ theme('img/oem/package-supply.png') }}" width="62"/></div><h3>{{ t('oem.comprehensive-packaging-and-delivery-services') }}</h3><p>{{ t('oem.full-packaging-solutions-with-nationwide-deliver') }}</p></li></ol></div>
</section>
<section class="sec">
<div class="wrap split split--rev">
<figure class="cutframe"><img alt="" decoding="async" height="496" loading="lazy" src="{{ theme('img/oem/supplements.webp') }}" width="900"/></figure>
<div><h2 class="h2">{{ t('oem.product-categories') }}</h2><h3 class="h3">{{ t('oem.dietary-supplements') }}</h3><p>{{ t('oem.multivitamins-collagen-glutathione-fiber-detox-f') }}</p></div>
</div>
</section>
<section class="sec cta">
<div class="wrap narrow center">
<h2 class="h2">{{ t('oem.build-your-brand-with-confidence') }}</h2>
<p>{{ t('oem.start-your-brand-today-with-posh') }}</p>
<p>{!! tx('oem.whether-you-are-launching-a-new') !!}</p>
<a class="btn btn--big" href="contact.html?topic=info">{{ t('oem.contact-us-2') }}</a>
</div>
</section>
@endsection

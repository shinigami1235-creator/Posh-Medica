@extends('layouts.site')
@section('content')
<section class="phead">
<p aria-hidden="true" class="phead__giant">{{ t('about.lab') }}</p><img alt="" aria-hidden="true" class="phead__deco" src="{{ theme('img/hero/tamarind-splash.webp') }}"/>
<div class="phead__in">
<nav aria-label="{{ t('about.breadcrumb') }}" class="crumbs"><a href="{{ lroute('home') }}">{{ t('about.home') }}</a> <span aria-hidden="true">/</span> <span>{{ t('about.about-us') }}</span></nav>
<h1>{{ t('about.innovative-health-and-beauty-solutions') }}</h1><p class="phead__sub">{{ t('about.posh-medica-lifetech-public-company-limited') }}</p>
</div>
</section>
<section class="sec" id="corporate">
<div class="wrap split">
<div class="split__text">
<p class="lead">{!! tx('about.for-over-15-years-posh-has') !!}</p>
<p>{{ t('about.driven-by-innovation-and-research-we') }}</p><p>{{ t('about.through-strategic-and-creative-marketing-we') }}</p><p>{{ t('about.at-our-core-we-prioritize-sustainability') }}</p><p>{{ t('about.with-a-passion-for-excellence-posh') }}</p>
</div>
<figure class="cutframe"><img alt="" decoding="async" height="785" loading="lazy" src="{{ theme('img/about/lab.webp') }}" width="695"/></figure>
</div>
@include('partials.about-stats')
</section>
<section class="sec sec--tint">
<div class="wrap vm">
<article class="spec"><h2>{{ t('about.vision') }}</h2><p>{{ t('about.leader-of-affordable-evidence-based-healthcare') }}</p></article>
<article class="spec spec--orange"><h2>{{ t('about.mission') }}</h2><p>{{ t('about.we-are-fully-committed-to-excellent') }}</p></article>
</div>
</section>
<section class="sec">
<div class="wrap split split--rev">
<figure class="cutframe"><img alt="" decoding="async" height="690" loading="lazy" src="{{ theme('img/about/production.webp') }}" width="693"/></figure>
<div><h2 class="h2">{{ t('about.research-development-by-specialized-scientists') }}</h2>
<dl class="figures"><div><dt>{{ t('about.researches-clinical-trials') }}</dt><dd>{!! tx('about.1-000-papers') !!}</dd></div><div><dt>{{ t('about.formula-developments') }}</dt><dd>{!! tx('about.500-projects') !!}</dd></div><div><dt>{{ t('about.fda-registrations') }}</dt><dd>{!! tx('about.200-formulations') !!}</dd></div><div><dt>{{ t('about.r-d-investment') }}</dt><dd>{!! tx('about.100-million-baht') !!}</dd></div></dl></div>
</div>
<div class="wrap split">
<div><h2 class="h2">{{ t('about.manufacturing-quality-control-at-global-standard') }}</h2>
<dl class="figures"><div><dt>{{ t('about.production-areas') }}</dt><dd>{!! tx('about.15-000-square-meters') !!}</dd></div><div><dt>{{ t('about.advanced-machines') }}</dt><dd>{!! tx('about.100-units') !!}</dd></div><div><dt>{{ t('about.production-capacity') }}</dt><dd>{!! tx('about.500-million-units-per-annum') !!}</dd></div><div><dt>{{ t('about.licensed-manufacturing') }}</dt><dd>{!! tx('about.13-categories') !!}</dd></div></dl>
<h3 class="h3">{{ t('about.environmental-management-system') }}</h3>
<ul class="chips"><li>{{ t('about.humidity-control') }}</li><li>{{ t('about.iso-class-ii') }}</li><li>{{ t('about.high-speed-door-system') }}</li><li>{{ t('about.possitive-pressure') }}</li><li>{{ t('about.temperature-control') }}</li></ul></div>
<figure class="cutframe"><img alt="" decoding="async" height="896" loading="lazy" src="{{ theme('img/about/factory.webp') }}" width="900"/></figure>
</div>
<div class="wrap certs"><h2 class="h3">{{ t('about.certified-by') }}</h2><div class="certs__row"><img alt="{{ t('about.asean-guidelines-for-cosmetic-gmp') }}" decoding="async" height="129" loading="lazy" src="{{ theme('img/about/cert-asean.webp') }}" width="129"/><img alt="{{ t('about.ghp') }}" decoding="async" height="148" loading="lazy" src="{{ theme('img/about/cert-ghp.webp') }}" width="260"/><img alt="{{ t('about.haccp') }}" decoding="async" height="148" loading="lazy" src="{{ theme('img/about/cert-haccp.webp') }}" width="260"/><img alt="{{ t('about.iso-22716') }}" decoding="async" height="129" loading="lazy" src="{{ theme('img/about/cert-iso22716.webp') }}" width="129"/><img alt="{{ t('about.fda') }}" decoding="async" height="173" loading="lazy" src="{{ theme('img/about/cert-fda.webp') }}" width="260"/><img alt="{{ t('about.halal') }}" decoding="async" height="173" loading="lazy" src="{{ theme('img/about/cert-halal.webp') }}" width="260"/></div></div>
</section>
<section class="sec sec--tint" id="milestone">
<div class="wrap"><h2 class="h2 center">{{ t('about.company-milestone') }}</h2>
@include('partials.milestones')</div>
</section>
<section class="sec" id="message">
<div class="wrap"><h2 class="h2">{{ t('about.message-from-company') }}</h2>
<div class="letters"><div aria-label="{{ t('about.messages') }}" class="letters__tabs" role="tablist"><button aria-controls="lp0" aria-selected="true" id="lt0" role="tab" tabindex="0"><img alt="{{ t('about.dr-mangkorn-dhanasarnsilp-2') }}" decoding="async" height="520" loading="lazy" src="{{ theme('img/about/mangkorn.webp') }}" width="422"/><span>{!! tx('about.dr-mangkorn-dhanasarnsilpchairman-of-the-board') !!}</span></button><button aria-controls="lp1" aria-selected="false" id="lt1" role="tab" tabindex="-1"><img alt="{{ t('about.mr-warodom-ngowsiri-2') }}" decoding="async" height="520" loading="lazy" src="{{ theme('img/about/warodom.webp') }}" width="422"/><span>{!! tx('about.mr-warodom-ngowsiriexecutive-chairman') !!}</span></button><button aria-controls="lp2" aria-selected="false" id="lt2" role="tab" tabindex="-1"><img alt="{{ t('about.wiwan-ngowsiri-md-2') }}" decoding="async" height="520" loading="lazy" src="{{ theme('img/about/wiwan.webp') }}" width="422"/><span>{!! tx('about.wiwan-ngowsiri-md-chief-executive-officer') !!}</span></button></div><article aria-labelledby="lt0" class="letter" id="lp0" role="tabpanel"><p class="letter__greet">{{ t('about.to-our-shareholders-and-stakeholders') }}</p><p>{{ t('about.in-the-year-2026-the-global') }}</p><p>{{ t('about.recognizing-these-circumstances-the-company-and') }}</p><p>{{ t('about.as-a-fully-integrated-manufacturer-and') }}</p><p>{{ t('about.the-company-is-committed-to-developing') }}</p><p>{{ t('about.over-the-past-year-we-have') }}</p><p>{{ t('about.the-company-also-prioritizes-building-strong') }}</p><p>{{ t('about.on-behalf-of-the-board-of') }}</p><footer class="letter__sign"><b>{{ t('about.dr-mangkorn-dhanasarnsilp') }}</b><span>{{ t('about.chairman-of-the-board') }}</span><span>{{ t('about.posh-medica-lifetech-public-company-limited-2') }}</span></footer></article><article aria-labelledby="lt1" class="letter" hidden="" id="lp1" role="tabpanel"><p>{{ t('about.the-path-to-achieving-our-vision') }}</p><p>{{ t('about.our-mission-is-a-social-contract') }}</p><p>{{ t('about.and-we-have-already-proven-that') }}</p><p>{!! tx('about.health-is-wealth') !!}</p><p>{{ t('about.at-posh-we-will-never-stop') }}</p><footer class="letter__sign"><b>{{ t('about.mr-warodom-ngowsiri') }}</b><span>{{ t('about.executive-chairman') }}</span><span>{{ t('about.posh-medica-lifetech-public-company-limited-3') }}</span></footer></article><article aria-labelledby="lt2" class="letter" hidden="" id="lp2" role="tabpanel"><p>{{ t('about.for-over-15-years-as-a') }}</p><p>{{ t('about.we-didn-t-create-this-brand') }}</p><p>{{ t('about.we-believe-supplements-should-delight-not') }}</p><p>{{ t('about.so-we-turned-routine-into-ritual') }}</p><p>{{ t('about.we-re-committed-to-progress-without') }}</p><p>{{ t('about.we-are-committed-to-sourcing-safe') }}</p><p>{{ t('about.this-isn-t-just-a-business') }}</p><p>{{ t('about.to-design-evidence-based-health-products') }}</p><p>{{ t('about.this-philosophy-shapes-how-we-grow') }}</p><footer class="letter__sign"><b>{{ t('about.wiwan-ngowsiri-md') }}</b><span>{{ t('about.chief-executive-officer') }}</span><span>{{ t('about.posh-medica-lifetech-public-company-limited-4') }}</span></footer></article></div>
</div>
</section>
<section class="sec sec--tint" id="org">
<div class="wrap"><h2 class="h2">{{ t('about.organization-chart') }}</h2>
<a class="orgchart" href="{{ theme('img/about/org-chart.webp') }}" rel="noopener" target="_blank"><img alt="{{ t('about.organization-chart-2') }}" decoding="async" height="1336" loading="lazy" src="{{ theme('img/about/org-chart.webp') }}" width="2000"/><span>{{ t('about.view-large') }}</span></a></div>
</section>
@endsection

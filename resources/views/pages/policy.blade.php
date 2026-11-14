@extends('layouts.site')
@section('content')
<section class="phead">
  <p class="phead__giant" aria-hidden="true">{{ t('ir.trust') }}</p>
  <div class="phead__in">
    <nav class="crumbs" aria-label="{{ t('nav.breadcrumb') }}"><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a> <span aria-hidden="true">/</span> <span>{{ t('nav.investor-relations') }}</span> <span aria-hidden="true">/</span> <span>{{ t('ir.corporate-governance') }}</span></nav>
    <h1>{{ t('ir.corporate-governance') }}</h1>
  </div>
</section>
<div class="wrap irgrid">
  <nav class="irnav" aria-label="{{ t('ir.corporate-governance') }}"><ul>@foreach ($all as $x)<li><a href="{{ lroute('policy', ['policy' => $x->slug]) }}" @if ($x->is($p)) aria-current="page" @endif>{{ $x->l('title') }}</a></li>@endforeach</ul>
    <p class="irnav__mail">{{ t('footer.for-shareholder') }}<br><a href="mailto:{{ setting('email_investor') }}">{{ setting('email_investor') }}</a></p></nav>
  <div><section class="irsec"><h2 class="h2">{{ $p->l('title') }}</h2>
    {!! \App\Support\Html::clean($p->l('body')) !!}
    @if ($p->documents)
    <ul class="docs">@foreach ($p->documents as $d)<li><a href="{{ media($d['file'] ?? null) }}" target="_blank" rel="noopener"><span class="doc__ic" aria-hidden="true">PDF</span><span>{{ app()->getLocale() === 'th' ? ($d['title_th'] ?? $d['title_en'] ?? '') : ($d['title_en'] ?? $d['title_th'] ?? '') }}</span><span class="doc__dl">{{ __('posh.download') }}</span></a></li>@endforeach</ul>
    @endif
  </section></div>
</div>
@endsection

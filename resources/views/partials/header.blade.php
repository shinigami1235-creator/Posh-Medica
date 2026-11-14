@php
$chev = '<svg width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
$here = request()->route()?->getName();
$nav = [
  ['nav.about-us', ['about', 'awards'], [['nav.corporate-info', lroute('about') . '#corporate'], ['nav.company-milestone', lroute('about') . '#milestone'], ['nav.message-from-company', lroute('about') . '#message'], ['nav.organization-chart', lroute('about') . '#org'], ['nav.awards-standards', lroute('awards')]]],
  ['nav.our-business', ['oem', 'product'], [['nav.product-overview', lroute('home') . '#products'], ['nav.supplements', lroute('home') . '#products'], ['nav.oem-and-odm', lroute('oem')]]],
  ['nav.investor-relations', ['policy', 'ir'], \App\Models\Policy::orderBy('sort')->get()->map(fn ($p) => [$p->l('title'), lroute('policy', ['policy' => $p->slug]), true])->all()],
  ['nav.blog-and-event', ['events', 'blog', 'post'], lroute('events')],
  ['nav.contact-us', ['contact'], lroute('contact')],
];
@endphp
<header class="top">
  <a class="top__logo" href="{{ lroute('home') }}" aria-label="{{ t('nav.posh-medica-home') }}"><img src="{{ theme('img/posh-logo-white.svg') }}" alt="Posh" width="77" height="34"></a>
  <nav class="top__nav" aria-label="{{ t('nav.main') }}"><ul class="nav">
  @foreach ($nav as $i => [$label, $names, $sub])
    @php $cur = in_array($here, $names, true) ? ' aria-current=page' : ''; @endphp
    @if (is_string($sub))
      <li><a class="nav__link" href="{{ $sub }}"{{ $cur }}>{{ t($label) }}</a></li>
    @else
      <li class="nav__group"><button class="nav__link" aria-expanded="false" aria-controls="sub{{ $i }}"{{ $cur }}>{{ t($label) }}{!! $chev !!}</button>
        <ul class="nav__sub" id="sub{{ $i }}">@foreach ($sub as $s)<li><a href="{{ $s[1] }}">{{ ($s[2] ?? false) ? $s[0] : t($s[0]) }}</a></li>@endforeach</ul></li>
    @endif
  @endforeach
  </ul>
    <a class="top__lang" href="{{ $alternates[$other] ?? url($other . '/home') }}" hreflang="{{ $other }}" lang="{{ $other }}">{{ strtoupper($other) }}</a></nav>
  <button class="top__burger" aria-label="{{ t('nav.menu') }}" aria-expanded="false"><svg width="22" height="22" viewBox="0 0 22 22" aria-hidden="true"><path d="M3 6h16M3 11h16M3 16h16" stroke="#26235e" stroke-width="2.4" stroke-linecap="round"/></svg></button>
</header>

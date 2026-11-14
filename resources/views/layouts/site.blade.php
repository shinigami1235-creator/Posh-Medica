@php
    $seo = $seo ?? \App\Support\Seo::make();
    $l = app()->getLocale();
    $other = $l === 'th' ? 'en' : 'th';
    $css = array_merge(['base', 'pages', 'buddy', 'laravel'], $css ?? []);
    $js = $js ?? [];
    $gtm = setting('gtm_id');
    $v = fn ($f) => @filemtime(public_path('themes/default/assets/' . $f)) ?: 1;
    $ld = json_encode(['@context' => 'https://schema.org', '@graph' => array_merge([[
    '@type' => 'Corporation', '@id' => url('/#org'), 'name' => t('meta.site-name'), 'legalName' => 'Posh Medica Lifetech Public Company Limited',
    'alternateName' => 'บริษัท พอช เมดิก้า ไลฟ์เทค จำกัด (มหาชน)', 'url' => url('th/home'), 'logo' => theme('img/posh-logo.png'),
    'tickerSymbol' => 'POSH', 'email' => setting('email_customer'), 'telephone' => setting('phone_intl'),
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => setting('street_' . $l), 'addressLocality' => setting('locality_' . $l), 'addressRegion' => $l === 'th' ? 'กรุงเทพมหานคร' : 'Bangkok', 'postalCode' => '10230', 'addressCountry' => 'TH'],
    'sameAs' => array_values(array_filter([setting('facebook'), setting('instagram'), setting('youtube'), setting('tiktok'), setting('x'), setting('line')])),
]], $seo['schema'])], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
@endphp
<!doctype html>
<html lang="{{ $l }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if ($seo['noindex'] || config('site.noindex'))<meta name="robots" content="noindex,nofollow">@endif
<link rel="canonical" href="{{ $seo['canonical'] }}">
@foreach ($seo['alternates'] as $lang => $href)<link rel="alternate" hreflang="{{ $lang }}" href="{{ $href }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $seo['alternates']['th'] ?? url('th/home') }}">
<meta property="og:site_name" content="{{ t('meta.site-name') }}">
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:locale" content="{{ $l === 'th' ? 'th_TH' : 'en_US' }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#26235e">
<link rel="icon" href="{{ theme('img/favicon.png') }}">
<link rel="apple-touch-icon" href="{{ theme('img/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<link rel="preload" href="{{ theme('fonts/LINESeedSansTH-Heavy.woff2') }}" as="font" type="font/woff2" crossorigin>
@foreach ($css as $c)<link rel="stylesheet" href="{{ theme('css/' . $c . '.css') }}?v={{ $v('css/' . $c . '.css') }}">@endforeach

<script type="application/ld+json">{!! $ld !!}</script>

{{-- Google Consent Mode: nothing is measured until the visitor accepts in the cookie banner. --}}
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
(function(){var c={};try{c=JSON.parse(localStorage.getItem('posh-consent')||'{}')}catch(e){}
gtag('consent','default',{ad_storage:c.marketing?'granted':'denied',ad_user_data:c.marketing?'granted':'denied',ad_personalization:c.marketing?'granted':'denied',analytics_storage:c.analytics?'granted':'denied',wait_for_update:500});})();
</script>
@if ($gtm)
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtm }}');</script>
@endif
@stack('head')
</head>
<body @isset($accent) style="--accent:{{ $accent[0] }};--accent-bg:{{ $accent[1] }};--accent-ink:{{ $accent[2] }}" @endisset>
@if ($gtm)<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtm }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>@endif
<a class="skip" href="#main">{{ t('nav.skip-to-content') }}</a>
@include('partials.header', ['other' => $other, 'alternates' => $seo['alternates']])
<main id="main">
@yield('content')
</main>
@include('partials.footer')
@include('partials.cookie-banner')
<script>
window.POSH_BASE = @json(asset('themes/default') . '/');
@isset($productsJs) window.POSH_PRODUCTS = @json($productsJs); @endisset
@isset($productUrls) window.POSH_PRODUCT_URL = function (id) { return (@json($productUrls))[id]; }; @endisset
@if ($l === 'th') window.POSH_T = @json(trans('posh.js', [], 'th')); @endif
</script>
<script src="{{ theme('js/site.js') }}?v={{ $v('js/site.js') }}" defer></script>
@foreach ($js as $j)<script src="{{ theme('js/' . $j . '.js') }}?v={{ $v('js/' . $j . '.js') }}" defer></script>@endforeach
<script src="{{ theme('js/cookies.js') }}?v={{ $v('js/cookies.js') }}" defer></script>
<script type="module" src="{{ theme('js/buddy.js') }}?v={{ $v('js/buddy.js') }}"></script>
@stack('scripts')
</body>
</html>

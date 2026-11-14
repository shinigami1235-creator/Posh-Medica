@php
  $l = in_array(request()->segment(1), ['th', 'en']) ? request()->segment(1) : 'th';
  app()->setLocale($l);
  $msg = __('posh.error');
@endphp
<!doctype html>
<html lang="{{ $l }}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>419 | Posh Medica</title>
<link rel="stylesheet" href="{{ theme('css/base.css') }}"><link rel="stylesheet" href="{{ theme('css/pages.css') }}"><link rel="stylesheet" href="{{ theme('css/laravel.css') }}">
</head><body>
<main class="oops"><img src="{{ theme('img/hero/glass-tamarind-drink.webp') }}" alt="" width="200"><p class="oops__code">419</p><h1>{{ $msg }}</h1>
<p><a class="btn" href="{{ url($l . '/home') }}">{{ __('posh.go_home') }}</a></p></main>
</body></html>

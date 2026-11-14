@extends('layouts.site')
@section('content')
<section class="phead"><div class="phead__in">
  <nav class="crumbs" aria-label="{{ t('nav.breadcrumb') }}"><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a> <span aria-hidden="true">/</span> <span>{{ $p->l('title') }}</span></nav>
  <h1>{{ $p->l('title') }}</h1></div></section>
<section class="sec"><div class="wrap narrow prose">{!! \App\Support\Html::clean($p->l('body')) !!}</div></section>
@endsection

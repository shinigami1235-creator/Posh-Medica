@extends('layouts.site')
@section('content')
<section class="pd">
  <div class="wrap pd__grid">
    <div class="pd__media">
      <img class="pd__pack" src="{{ media($photos[0] ?? null) }}" alt="{{ $p->l('name') }}" width="600" height="600">
      @if (count($photos) > 1)
      <div class="pd__thumbs">@foreach ($photos as $i => $ph)<button type="button" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" aria-label="{{ __('posh.photo') }} {{ $i + 1 }}" data-src="{{ media($ph) }}"><img src="{{ media($ph) }}" alt="" loading="lazy"></button>@endforeach</div>
      @endif
    </div>
    <div class="pd__body">
      <nav class="crumbs" aria-label="{{ t('nav.breadcrumb') }}"><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a> <span aria-hidden="true">/</span> <a href="{{ lroute('home') }}#products">{{ __('posh.supplements') }}</a> <span aria-hidden="true">/</span> <span>{{ __('posh.groups.' . $p->group) }}</span></nav>
      <p class="pd__meta"><span class="pd__no">No. {{ str_pad($p->id, 2, '0', STR_PAD_LEFT) }}</span><span class="pd__form">{{ __('posh.forms.' . $p->form) }}</span></p>
      <h1 class="pd__name">{{ $p->l('name') }}</h1>
      @if ($p->l('desc'))<h2 class="h3 pd__dh">{{ __('posh.descriptions') }}</h2><p class="pd__desc">{{ $p->l('desc') }}</p>@endif
      @if ($p->l('ingredients'))<h2 class="h3 pd__ih">{{ __('posh.ingredients') }}</h2><ul class="pd__formula">@foreach ($p->l('ingredients') as $x)<li>{{ $x }}</li>@endforeach</ul>@endif
      @if ($p->badges)<ul class="pd__badges">@foreach ($p->badges as $b)<li>{{ __('posh.badges.' . $b) === 'posh.badges.' . $b ? $b : __('posh.badges.' . $b) }}</li>@endforeach</ul>@endif
      <a class="btn" href="{{ lroute('home') }}#where">{{ __('posh.where_to_buy') }}</a>
    </div>
  </div>
  @if ($more->isNotEmpty())
  <div class="wrap"><h2 class="h2">{{ __('posh.more_from_group') }}</h2>
    <ul class="pd__more">@foreach ($more as $x)<li><a href="{{ $x->url() }}"><img src="{{ media($x->image) }}" alt="" loading="lazy">{{ $x->l('name') }}</a></li>@endforeach</ul></div>
  @endif
</section>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.pd__thumbs button').forEach(b => b.addEventListener('click', () => {
  document.querySelector('.pd__pack').src = b.dataset.src;
  document.querySelectorAll('.pd__thumbs button').forEach(x => x.setAttribute('aria-pressed', x === b));
}));
</script>
@endpush

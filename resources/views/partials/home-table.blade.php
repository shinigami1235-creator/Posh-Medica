{{-- The periodic table of products. The cells are drawn by table.js from the products list the page carries; the links below are for search engines and people without JavaScript. --}}
@php $forms = $products->countBy('form'); @endphp
<div class="ptable">
<div class="ptable__head">
<h2 id="tableTitle">{{ t('home.supplement') }}</h2>
<p class="ptable__lede">{{ t('home.premium-grade-extracts-expertly-researched-and-developed') }}</p>
<div class="ptable__filters" role="group" aria-label="{{ t('home.filter-by-form') }}">
<button class="chip is-on" data-form="all">{{ t('home.all') }} <b>{{ $products->count() }}</b></button>
@foreach (['powder', 'capsule', 'jelly'] as $f)<button class="chip" data-form="{{ $f }}">{{ __('posh.forms.' . $f) }} <b>{{ $forms[$f] ?? 0 }}</b></button>@endforeach
</div>
</div>
<ul class="legend" aria-label="{{ t('home.groups') }}">
@foreach (['fiber', 'care', 'skin', 'pung'] as $g)<li data-g="{{ $g }}">{{ __('posh.groups.' . $g) }}</li>@endforeach
</ul>
<ol class="ptable__cells" aria-label="{{ $products->count() }} {{ __('posh.supplements') }}">
@foreach ($products as $p)<li><a href="{{ $p->url() }}">{{ $p->l('name') }}</a></li>@endforeach
</ol>
</div>

<ul class="wall">
@foreach ($awards[$kind] ?? [] as $a)
<li class="frame"><div class="frame__img"><img src="{{ media($a->image) }}" alt="{{ $a->l('title') }}" loading="lazy" decoding="async"></div><p>{{ $a->l('title') }}</p></li>
@endforeach
</ul>

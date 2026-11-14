{{-- Shop logos on shelves. Shelf 1, 2 and 3 are set per logo under Stores in the admin; a logo with a link opens the shop. --}}
@php $packs = [[2, 16], [37, 53], [10, 55]]; $byId = $products->keyBy('id'); @endphp
<div class="store">
@foreach ($stores->groupBy('shelf') as $n => $row)
  @php [$a, $b] = $packs[($loop->index) % 3]; @endphp
  <ul class="shelf__row">
    @if ($byId->has($a))<li class="shelf__pack" aria-hidden="true"><img src="{{ media($byId[$a]->image) }}" alt="" loading="lazy"></li>@endif
    @foreach ($row as $s)
      <li>@if ($s->url)<a class="shelf__item" href="{{ $s->url }}" target="_blank" rel="noopener">@else<span class="shelf__item">@endif<img src="{{ media($s->logo) }}" alt="{{ $s->name }}" loading="lazy" decoding="async">@if ($s->url)</a>@else</span>@endif</li>
    @endforeach
    @if ($byId->has($b))<li class="shelf__pack" aria-hidden="true"><img src="{{ media($byId[$b]->image) }}" alt="" loading="lazy"></li>@endif
  </ul>
@endforeach
</div>

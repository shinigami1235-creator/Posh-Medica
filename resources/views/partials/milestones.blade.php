<ol class="drops">
@foreach ($milestones as $m)
<li class="drop">
  <span class="drop__year">{{ $m->year }}</span>
  <div class="drop__card">@if ($m->image)<img src="{{ media($m->image) }}" alt="{{ $m->l('title') }}" loading="lazy" decoding="async">@endif<h3>{{ $m->l('title') }}</h3><p>{{ $m->l('text') }}</p></div>
</li>
@endforeach
</ol>

<ul class="readouts wrap" aria-label="{{ t('home.posh-medica-in-numbers') }}">
@foreach ($stats as $i => $s)
<li class="readout">
  <svg class="readout__tube" viewBox="0 0 40 150" aria-hidden="true"><defs><clipPath id="tc{{ $i }}"><path d="M8 6h24v122a12 12 0 0 1-24 0z"/></clipPath></defs>
    <g clip-path="url(#tc{{ $i }})"><rect class="readout__fill" x="0" y="{{ round(6 + 122 * (1 - $s->fill)) }}" width="40" height="150"/></g>
    <path d="M8 6h24v122a12 12 0 0 1-24 0zM4 6h32" fill="none" stroke="#26235e" stroke-width="3.5" stroke-linecap="round"/>
    <path d="M32 40h-7M32 70h-7M32 100h-7" stroke="#26235e" stroke-width="2.5" stroke-linecap="round"/></svg>
  <p class="readout__label">{{ $s->l('label') }}</p><p class="readout__value">{{ $s->value }}</p><p class="readout__unit">{{ $s->l('unit') }}</p>
</li>
@endforeach
</ul>

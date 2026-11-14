{{-- Test tubes on the home page: label, value and unit come from Stats in the admin; "fill" sets how full each tube is. --}}
<ul class="wrap gauges" aria-label="{{ t('home.posh-medica-in-numbers') }}">
@foreach ($stats as $i => $s)
@php [$c1, $c2] = array_pad(explode(',', $s->color ?: '#f39a2c,#d9700e'), 2, '#d9700e'); $u = 'tb' . $i; @endphp
<li class="gauge" style="--f:{{ $s->fill }};--liq:{{ trim($c1) }};--liq2:{{ trim($c2) }}"><svg class="gauge__tube" viewBox="0 0 48 170" aria-hidden="true" focusable="false">
<defs><clipPath id="{{ $u }}c"><path d="M12 16V140a12 12 0 0 0 24 0V16z"/></clipPath>
<linearGradient id="{{ $u }}l" x1="0" x2="1"><stop offset="0" stop-color="var(--liq2)"/><stop offset=".3" stop-color="var(--liq)"/><stop offset=".65" stop-color="var(--liq)"/><stop offset="1" stop-color="var(--liq2)"/></linearGradient></defs>
<path d="M9 12V140a15 15 0 0 0 30 0V12" fill="url(#glass)"/>
<g clip-path="url(#{{ $u }}c)"><g class="tb__l"><g class="tb__w"><path d="M-48 2 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 V220 H-48 Z" fill="url(#{{ $u }}l)"/><path d="M-48 2 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0 q6 -4 12 0 t12 0" fill="none" stroke="rgba(255,255,255,.65)" stroke-width="1.6"/></g>
<rect x="0" y="0" width="48" height="200" fill="url(#deep)"/>@foreach ([[19, 128, 2.4, 0], [28, 140, 1.8, .5], [23, 120, 1.3, .9], [30, 110, 2, 1.3]] as [$x, $y, $r, $d])<circle class="tb__b" cx="{{ $x }}" cy="{{ $y }}" r="{{ $r }}" fill="url(#bub)" style="animation-delay:{{ $d }}s"/>@endforeach</g><circle cx="14.6" cy="142" r="1.6" fill="url(#bub)"/><circle cx="33.4" cy="132" r="1.2" fill="url(#bub)"/></g>
<g class="tb__ticks">@for ($k = 0; $k < 9; $k++)<path d="M{{ $k % 2 ? 30 : 27 }} {{ 40 + $k * 12 }}H35"/>@endfor</g>
<rect x="14.5" y="22" width="3.6" height="112" rx="1.8" fill="#fff" opacity=".8"/><rect x="31.2" y="26" width="1.6" height="70" rx=".8" fill="#fff" opacity=".45"/>
<path d="M16 146a9 9 0 0 0 9 5" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" opacity=".7"/>
<path d="M9 12V140a15 15 0 0 0 30 0V12" fill="none" stroke="var(--navy)" stroke-width="2.6"/>
<rect x="5" y="7" width="38" height="7" rx="3.5" fill="#fff" stroke="var(--navy)" stroke-width="2.6"/></svg><span class="gauge__label">{{ $s->l('label') }}</span><b class="gauge__value">{{ $s->value }}</b><span class="gauge__unit">{{ $s->l('unit') }}</span></li>
@endforeach
</ul>

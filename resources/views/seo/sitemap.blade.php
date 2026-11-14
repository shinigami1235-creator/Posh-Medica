{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($pages as $p) @foreach (['th', 'en'] as $l)
  <url>
    <loc>{{ $p[$l] }}</loc>
    <xhtml:link rel="alternate" hreflang="th" href="{{ $p['th'] }}"/>
    <xhtml:link rel="alternate" hreflang="en" href="{{ $p['en'] }}"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $p['th'] }}"/>
@if ($p['lastmod'])    <lastmod>{{ \Illuminate\Support\Carbon::parse($p['lastmod'])->toAtomString() }}</lastmod>
@endif
  </url>
@endforeach @endforeach
</urlset>

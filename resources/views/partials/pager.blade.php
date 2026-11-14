@if ($paginator->hasPages())
<nav class="pager" aria-label="Pages">
  @if (! $paginator->onFirstPage())<a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">‹</a>@endif
  @foreach ($elements as $el)
    @if (is_string($el))<span aria-hidden="true">{{ $el }}</span>@endif
    @if (is_array($el)) @foreach ($el as $page => $url)
      @if ($page == $paginator->currentPage())<span aria-current="page">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
    @endforeach @endif
  @endforeach
  @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">›</a>@endif
</nav>
@endif

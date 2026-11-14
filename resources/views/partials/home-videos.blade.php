{{-- The little TV. Each YouTube video added under Videos in the admin gets a channel button. Nothing loads from YouTube until someone presses play. --}}
@if ($videos->isNotEmpty())
<div class="tv" data-videos="{{ $videos->pluck('youtube_id')->implode(',') }}">
  <span class="tv__ant tv__ant--l" aria-hidden="true"></span><span class="tv__ant tv__ant--r" aria-hidden="true"></span>
  <div class="tv__body">
    <div class="tv__screen">
      <button class="tv__play" type="button" aria-label="{{ t('home.play-the-posh-medica-video') }}">
        <img class="tv__thumb" src="https://i.ytimg.com/vi/{{ $videos->first()->youtube_id }}/hqdefault.jpg" alt="{{ $videos->first()->l('title') }}" loading="lazy" width="480" height="360">
        <span class="tv__btn" aria-hidden="true"><svg viewBox="0 0 24 24" width="34" height="34"><path d="M8 5.5v13l11-6.5z" fill="currentColor"/></svg></span>
      </button>
    </div>
    <div class="tv__panel">
      <div class="tv__chs" role="group" aria-label="{{ t('home.videos') }}">
        @foreach ($videos as $i => $v)<button class="tv__ch" type="button" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" data-i="{{ $i }}" @if ($v->l('title')) title="{{ $v->l('title') }}" @endif>{{ $i + 1 }}</button>@endforeach
      </div>
      <span class="tv__grill" aria-hidden="true"></span>
      <span class="tv__light" aria-hidden="true"></span>
    </div>
  </div>
  <span class="tv__foot tv__foot--l" aria-hidden="true"></span><span class="tv__foot tv__foot--r" aria-hidden="true"></span>
</div>
@endif

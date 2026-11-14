<ul class="snaps">
@foreach ($latest as $post)
<li class="snap"><a href="{{ $post->url() }}"><div class="post__img"><img src="{{ media($post->cover) }}" alt="" width="600" height="440" loading="lazy" decoding="async"><span class="post__date">{{ site_date($post->published_on) }}</span></div><h3>{{ $post->l('title') }}</h3><span class="post__more">{{ __('posh.read_more') }}</span></a></li>
@endforeach
</ul>

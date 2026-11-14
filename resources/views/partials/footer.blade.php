@php $social = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'Youtube', 'line' => 'Line', 'tiktok' => 'Tiktok', 'x' => 'X']; @endphp
<footer class="foot">
  <div class="foot__wrap">
    <div class="foot__co">
      <img src="{{ theme('img/posh-logo-white.svg') }}" alt="Posh" width="110" height="49" loading="lazy">
      <p class="foot__name">{{ t('footer.company-name') }}</p>
      <p>{{ t('footer.address') }}</p>
      <p>{{ t('footer.for-customer') }} <a href="mailto:{{ setting('email_customer') }}">{{ setting('email_customer') }}</a><br>
         {{ t('footer.for-shareholder') }} <a href="mailto:{{ setting('email_investor') }}">{{ setting('email_investor') }}</a><br>
         <a href="tel:{{ preg_replace('/\D/', '', setting('phone', '')) }}">{{ setting('phone') }}</a></p>
      <ul class="foot__social">@foreach ($social as $k => $n) @if (setting($k))<li><a href="{{ setting($k) }}" target="_blank" rel="noopener" aria-label="{{ $n }}"><img src="{{ theme('img/social/' . ($k === 'x' ? 'x-twitter' : $k) . '.png') }}" alt="" width="36" height="36" loading="lazy"></a></li>@endif @endforeach</ul>
    </div>
    <ul class="foot__links"><li><a href="{{ lroute('home') }}">{{ t('nav.home') }}</a></li><li><a href="{{ lroute('about') }}">{{ t('nav.about-us') }}</a></li><li><a href="{{ lroute('home') }}#products">{{ t('nav.our-business') }}</a></li><li><a href="{{ lroute('ir') }}">{{ t('nav.investor-relations') }}</a></li><li><a href="{{ lroute('events') }}">{{ t('nav.blog-and-event') }}</a></li><li><a href="{{ lroute('contact') }}">{{ t('nav.contact-us') }}</a></li></ul>
  </div>
  <div class="foot__legal">
    <p>{{ t('footer.copyright') }}</p>
    <ul><li><a href="{{ lroute('sitemap') }}">{{ t('nav.sitemap') }}</a></li><li><a href="{{ lroute('legal', ['page' => 'terms-conditions']) }}">{{ t('nav.terms-conditions') }}</a></li>
        <li><a href="{{ lroute('legal', ['page' => 'privacy-policy']) }}">{{ t('nav.privacy-policy') }}</a></li><li><a href="{{ lroute('legal', ['page' => 'cookie-policy']) }}">{{ t('nav.cookie-policy') }}</a></li>
        <li><button type="button" class="linkish" data-cookie-settings>{{ __('posh.cookie.settings') }}</button></li></ul>
  </div>
</footer>

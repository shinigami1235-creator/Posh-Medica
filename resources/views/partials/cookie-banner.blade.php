{{-- PDPA cookie choice. Until the visitor chooses, Tag Manager runs with analytics and ads turned off (Google Consent Mode). --}}
<div class="cookies" data-cookies hidden role="dialog" aria-live="polite" aria-label="{{ __('posh.cookie.settings') }}">
  <p>{{ __('posh.cookie.text') }} <a href="{{ lroute('legal', ['page' => 'cookie-policy']) }}">{{ __('posh.cookie.policy') }}</a></p>
  <div class="cookies__choose" data-cookies-choose hidden>
    <label class="cookies__row"><input type="checkbox" checked disabled> <span><b>{{ __('posh.cookie.necessary') }}</b> {{ __('posh.cookie.necessary_note') }}</span></label>
    <label class="cookies__row"><input type="checkbox" name="analytics"> <span><b>{{ __('posh.cookie.analytics') }}</b> {{ __('posh.cookie.analytics_note') }}</span></label>
    <label class="cookies__row"><input type="checkbox" name="marketing"> <span><b>{{ __('posh.cookie.marketing') }}</b> {{ __('posh.cookie.marketing_note') }}</span></label>
  </div>
  <div class="cookies__btns">
    <button type="button" class="btn btn--ghost" data-cookies-reject>{{ __('posh.cookie.reject') }}</button>
    <button type="button" class="btn btn--ghost" data-cookies-open>{{ __('posh.cookie.choose') }}</button>
    <button type="button" class="btn btn--ghost" data-cookies-save hidden>{{ __('posh.cookie.save') }}</button>
    <button type="button" class="btn" data-cookies-accept>{{ __('posh.cookie.accept') }}</button>
  </div>
</div>

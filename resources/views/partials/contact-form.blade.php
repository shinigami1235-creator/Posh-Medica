<form class="form" id="form" method="post" action="{{ lroute('contact.send') }}" enctype="multipart/form-data">
@csrf
<h2 class="h2">{{ t('contact.contact-form') }}</h2><p>{{ t('contact.please-fill-in-the-form-below') }}</p>
@if (session('sent'))<p class="form__ok" role="status">{{ __('posh.sent') }}</p>@endif
@if ($errors->any())<ul class="form__errors" role="alert">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<label>{{ t('contact.topic') }}<select name="topic" required><option value="">{{ t('contact.choose-a-topic') }}</option>
@foreach (\App\Http\Controllers\ContactController::TOPICS as $k)<option value="{{ $k }}" @selected(old('topic', request('topic')) === $k)>{{ __('posh.topics.' . $k) }}</option>@endforeach</select></label>
<label>{{ t('contact.full-name') }}<input name="name" required autocomplete="name" maxlength="120" value="{{ old('name') }}"></label>
<div class="form__row"><label>{{ t('contact.email') }}<input type="email" name="email" required autocomplete="email" maxlength="160" value="{{ old('email') }}"></label>
<label>{{ t('contact.telephone') }}<input type="tel" name="phone" autocomplete="tel" inputmode="tel" maxlength="30" value="{{ old('phone') }}"></label></div>
<label>{{ t('contact.message') }}<textarea name="message" rows="5" required maxlength="4000">{{ old('message') }}</textarea></label>
<label>{{ t('contact.attachment') }}<input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
<div class="hp" aria-hidden="true"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
<label class="check"><input type="checkbox" name="consent" value="1" required @checked(old('consent'))> <span>{{ t('contact.i-have-read-and-accepted-the') }} <a href="{{ lroute('legal', ['page' => 'privacy-policy']) }}" target="_blank" rel="noopener">{{ t('nav.privacy-policy') }}</a></span></label>
<button class="btn btn--big" type="submit">{{ t('contact.send') }}</button>
</form>

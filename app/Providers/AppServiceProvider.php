<?php

namespace App\Providers;

use App\Models\{PageText, Redirect, Setting};
use App\Support\{Html, Settings, Texts};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // edits in the admin show on the site straight away
        PageText::saving(function ($t) { if ($t->is_html) { $t->th = Html::clean($t->th); $t->en = Html::clean($t->en); } });
        PageText::saved(fn () => Texts::forget());
        PageText::deleted(fn () => Texts::forget());
        Setting::saved(fn () => Settings::forget());
        Redirect::saved(fn () => Cache::forget('redirects'));
        Redirect::deleted(fn () => Cache::forget('redirects'));
    }
}

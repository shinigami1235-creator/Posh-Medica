<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    private static ?array $all = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        self::$all ??= Cache::rememberForever('settings', fn () => Setting::pluck('value', 'key')->all());
        return filled(self::$all[$key] ?? null) ? self::$all[$key] : $default;
    }

    public static function forget(): void
    {
        self::$all = null;
        Cache::forget('settings');
    }
}

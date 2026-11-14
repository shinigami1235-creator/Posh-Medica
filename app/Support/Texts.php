<?php

namespace App\Support;

use App\Models\PageText;
use Illuminate\Support\Facades\Cache;

/** All page texts, loaded once and kept in the cache until someone edits one. */
class Texts
{
    private static ?array $all = null;

    public static function get(string $key): string
    {
        self::$all ??= Cache::rememberForever('page_texts', fn () => PageText::all(['key', 'th', 'en'])->keyBy('key')->map(fn ($r) => ['th' => $r->th, 'en' => $r->en])->all());
        $row = self::$all[$key] ?? null;
        if (! $row) return app()->isProduction() ? '' : "[{$key}]";
        $l = app()->getLocale();
        return (string) (filled($row[$l]) ? $row[$l] : $row[$l === 'th' ? 'en' : 'th']);
    }

    public static function forget(): void
    {
        self::$all = null;
        Cache::forget('page_texts');
    }
}

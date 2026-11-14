<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Localized;

    protected $guarded = [];
    protected $casts = ['ingredients_th' => 'array', 'ingredients_en' => 'array', 'badges' => 'array', 'photos' => 'array', 'is_published' => 'boolean'];

    public const GROUPS = ['fiber' => 'Fiber', 'care' => 'Health Care', 'skin' => 'Skin', 'pung' => 'Pungtalai Series'];
    public const FORMS = ['powder' => 'Powder', 'capsule' => 'Capsule', 'jelly' => 'Jelly'];

    public function scopePublished($q) { return $q->where('is_published', true); }

    public function url(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        return url($locale . '/products/supplements/' . $this->id . '/' . rawurlencode($this->{'slug_' . $locale}));
    }
}

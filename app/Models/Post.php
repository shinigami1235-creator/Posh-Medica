<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Localized;

    protected $guarded = [];
    protected $casts = ['gallery' => 'array', 'published_on' => 'date', 'is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true)->where('published_on', '<=', now()); }

    public function url(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        return url($locale . '/updates/blog-and-event/' . $this->kind . '/' . $this->id . '/' . rawurlencode($this->slug));
    }
}

<?php

namespace App\Models\Concerns;

/** Reads the Thai or English column for the language of the page, falling back to the other one when empty. */
trait Localized
{
    public function l(string $field, ?string $locale = null): mixed
    {
        $locale ??= app()->getLocale();
        $other = $locale === 'th' ? 'en' : 'th';
        $value = $this->{$field . '_' . $locale};

        return blank($value) ? $this->{$field . '_' . $other} : $value;
    }
}

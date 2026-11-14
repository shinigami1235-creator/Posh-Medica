<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/** Cleans HTML typed in the admin so a pasted script or style can never reach the site. */
class Html
{
    public static function clean(?string $html): ?string
    {
        if (blank($html)) return $html;
        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowElement('a', ['href', 'target', 'rel'])
            ->allowElement('img', ['src', 'alt', 'width', 'height', 'loading'])
            ->allowElement('span', ['class', 'lang'])
            ->allowElement('iframe', ['src', 'title', 'allow', 'allowfullscreen'])
            ->allowRelativeLinks()->allowRelativeMedias()
            ->allowLinkSchemes(['https', 'http', 'mailto', 'tel'])
            ->forceAttribute('a', 'rel', 'noopener');
        return (new HtmlSanitizer($config))->sanitize($html);
    }
}

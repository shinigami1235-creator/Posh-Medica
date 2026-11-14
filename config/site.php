<?php

/* Settings that belong to the server rather than the content. Content settings (phone, emails, Tag Manager ID) are edited in the admin. */
return [
    // Seconds browsers remember to use HTTPS only. Start with 86400 (a day) and raise to 31536000 once every address works over HTTPS.
    'hsts' => env('SITE_HSTS', 0),
    // true on staging copies, so search engines don't index them
    'noindex' => env('SITE_NOINDEX', false),
    // where contact form messages go when no email is set in the admin
    'contact_to' => env('SITE_CONTACT_TO', 'customer@poshmedica.com'),
    // contact form messages and attachments are deleted after this many days (PDPA: keep only what you need)
    'enquiry_days' => env('SITE_ENQUIRY_DAYS', 365),
];

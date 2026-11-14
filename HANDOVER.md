# Posh Medica website: handover

This is the new poshmedica.co.th in Thai and English, built with Laravel, the same framework the current site runs on. It is written for whoever installs and runs it: Posh Medica's IT team or ShareInvestor Thailand. Read it once before you start, and do every step on a staging copy before the live server.

## Who does what

- **IT or the hosting company** installs the site, runs the server, the database, HTTPS, email sending, backups and updates.
- **Posh Medica staff** edit the content at `/admin`: products, blog and event posts, awards, milestones, numbers, stores, videos, policies and every piece of page text, in Thai and English. None of this needs a developer.
- **Posh Medica's legal team** checks the privacy, cookie and terms pages. Their text was copied from the current site unchanged.

## What stays the same

- **Addresses.** Every page keeps its current address, such as `/th/about-us/corporate-info` and `/en/products/supplements/2/tamarind-fiber`. Pages that are now part of a bigger page, like `/th/about-us/company-milestone`, send a permanent redirect to the right spot on it. `tests/fixtures/old-urls.txt` lists all 198 old addresses, and the tests check each one.
- **Thai is the default.** `/` and `/th` go to `/th/home`, as they do now.
- **Uploaded files keep their addresses.** The current site serves photos and PDFs from `/storage/...`. This site serves the same folder at the same address, so copying it across (step 6) keeps every old photo and PDF link working.
- **Tag Manager.** The site loads the current container, GTM-5FNQ6QBL, so existing reports continue. It now waits for the visitor's cookie choice first (see PDPA below).

## What the server needs

- PHP 8.3 or newer with the extensions `gd`, `intl`, `mbstring`, `pdo_mysql`, `fileinfo`, `zip`, `xml`, `curl` and `openssl`.
- Composer 2.
- MySQL 8 or MariaDB 10.6 or newer.
- HTTPS.
- Outgoing email (SMTP). Contact form alerts and admin password resets use it.
- Cron, for the daily job that deletes old contact messages.
- The web server's document root set to the `public` folder. Nothing outside `public` may be reachable from the web.

## Install

1. Copy the project to the server, for example `/var/www/poshmedica`, then install the PHP libraries:
   ```
   composer install --no-dev --optimize-autoloader
   ```
2. Create the settings file and the app key:
   ```
   cp .env.example .env
   php artisan key:generate
   ```
   Fill in the database, mail and `APP_URL` lines in `.env`. On staging also set `SITE_NOINDEX=true`, so Google does not index the copy.
3. Create the tables:
   ```
   php artisan migrate --force
   ```
4. Link the public storage folder and publish the admin's files:
   ```
   php artisan storage:link
   php artisan filament:assets
   ```
5. Import the content and our photos:
   ```
   php artisan posh:import
   ```
   It loads every page text, product, post, policy, award, milestone, number, store and video in both languages. It can be run again at any time and updates each item in place. Add `--keep-edits` once staff have started editing, so their changes to page texts stay.
6. Copy the current site's uploads into `storage/app/public`, keeping the folder names. On the current server these are the files behind `https://www.poshmedica.co.th/storage/...`, for example `storage/updates/blog-and-event/2026/08/...` and `storage/downloads/corporate-governance/...`. The import already includes the post covers, the policy PDFs and one photo per post, so the site works without this step. The other gallery photos of older posts only show after it.
7. Create the first admin account and sign in at `/admin`:
   ```
   php artisan make:filament-user
   ```
   On first sign-in the admin asks every account to set up two-step login with an authenticator app (Google Authenticator, Microsoft Authenticator or similar). Each person should save their recovery codes. Keep one administrator's codes in a safe place, since a lost phone without them locks that person out.
8. Add the scheduler to cron. `deploy/crontab.txt` has the line.
9. Point the web server at `public`. Apache reads `public/.htaccess` and needs nothing more. For nginx, `deploy/nginx.conf` is an example.
10. Cache the configuration for speed. Run this again after every update:
    ```
    php artisan optimize
    ```
11. Run the checks:
    ```
    php artisan test
    ```
    All tests should pass. They visit every old address, the sitemap, the contact form and every admin screen.

## Going live

1. Lower the DNS TTL of www.poshmedica.co.th a day ahead, or prepare the CloudFront origin change if the site stays behind CloudFront.
2. On staging, open the pages in both languages on a phone and a computer, send the contact form once, and add and remove a test post in the admin.
3. Set `SITE_NOINDEX=false` and the real `APP_URL` on the live server, run `php artisan optimize`, then switch DNS or the CloudFront origin.
4. In Google Search Console, submit `https://www.poshmedica.co.th/sitemap.xml`.
5. After a week with everything on HTTPS, set `SITE_HSTS=31536000`.

## Editing the site

Staff sign in at `/admin`. The menu is in Thai and English.

| Menu | What it changes |
|---|---|
| Page texts | Every heading, paragraph, button and label on the pages, in Thai and English. Filter by page to find one. |
| YouTube videos | The videos on the little TV on the home page. Paste the YouTube link. |
| Products | The periodic table and each product page: names, descriptions, ingredients, badges, photos, the address name and the Google title and description. Drag rows to change the order. |
| Blog and events | Posts with cover, gallery, text and date. Thai is required; English is optional and the Thai shows when it is empty. |
| Awards and standards, Milestones, Numbers, Stores and partners, Governance policies | The matching parts of the About, Awards, Investor Relations and home pages. |
| Contact form messages | Messages from the contact form, with any attached file. A copy also goes by email to the address in Settings. |
| Legal pages | Privacy policy, terms and conditions, cookie policy. |
| Redirects | Send an old address to a new one, for example after renaming a product. |
| Settings | Phone, emails, address, social links and the Tag Manager ID. |
| Admin accounts | Who can sign in. |

## SEO

- Each page has its own title, description, canonical address, Thai and English alternates (hreflang) and social sharing preview.
- Structured data tells Google the company (with the POSH ticker, address and social profiles), each product, each post and the breadcrumbs.
- `/sitemap.xml` lists every page in both languages and updates itself when content changes. `/robots.txt` points to it and keeps `/admin` out.
- Products and posts each have an optional Google title and description in the admin. When empty, the name and the first text are used.

## PDPA

- **Cookies.** A banner asks every visitor once a year: accept all, necessary only, or choose analytics and marketing separately. Tag Manager runs in Google Consent Mode, so Google Analytics and ad tags only store cookies after the visitor allows them. A "Cookie settings" link in the footer reopens the choice. Tags added inside Tag Manager must respect consent (built-in consent checks, or the `consent_update` event the banner sends).
- **Contact form.** It asks for consent to the privacy policy before sending. Messages and attachments are deleted after 365 days (`SITE_ENQUIRY_DAYS`). Attachments are stored outside the public folder and only open from the admin.
- **The privacy policy text** is the current site's. Posh Medica's legal team should confirm it covers the contact form and the analytics cookies.

## Security

- Two-step login is required for every admin account (`ADMIN_REQUIRE_2FA=true`).
- Text with formatting typed in the admin is cleaned when saved and shown, so a pasted script can't run on the site.
- The contact form has a hidden trap field for bots and allows five sends per visitor every ten minutes.
- The site sends the same security headers as the current one, plus a content security policy that lists Tag Manager, YouTube, TikTok and Google Maps. A new third-party tool added in Tag Manager may need its address added in `app/Http/Middleware/SecurityHeaders.php`.
- Back up the database and `storage/app` every day, and keep 30 days.
- Keep `APP_DEBUG=false` on the live server.

## Updating the code

- Run `composer update` for Laravel and Filament security releases, then `php artisan migrate --force`, `php artisan filament:assets` and `php artisan optimize`, then `php artisan test`.
- The design lives in `public/themes/default/assets` (CSS, JavaScript, fonts and theme images), the same folder layout as the current site's theme. Page layouts are in `resources/views`.

## Where things are

| Path | What it is |
|---|---|
| `routes/web.php` | Every address of the site, with the old ones. |
| `app/Http/Controllers` | One controller per kind of page. |
| `resources/views/pages` | Page templates. `t('key')` prints a page text in the visitor's language. |
| `resources/views/partials` | Header, footer, cookie banner, contact form and the parts filled from the database. |
| `app/Filament/Resources` | The admin screens. |
| `app/Models` | One model per table. Text columns end in `_th` and `_en`. |
| `database/migrations` | The tables. |
| `database/content` | The content and photos `posh:import` loads. |
| `lang/th`, `lang/en` | Short labels the templates and scripts use (product groups, forms, badges, cookie banner). |
| `config/site.php` | Server-side site settings from `.env`. |
| `tests/Feature/SiteTest.php` | The checks `php artisan test` runs. |

## Before launch, for Posh Medica to confirm

- The titles of the two YouTube videos, and whether to show more.
- Links to the official Shopee and Lazada stores. The store logos without a link stay as plain logos.
- The Thai text added for this design: the flavour picker, the TV, the companion's lines, the cookie banner and the product badges such as "ไม่มีน้ำตาล".
- Whether the English versions of posts should be written. They show the Thai until then, as on the current site.

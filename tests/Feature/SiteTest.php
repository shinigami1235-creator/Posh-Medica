<?php

namespace Tests\Feature;

use App\Mail\NewEnquiry;
use App\Models\{Enquiry, Post, Product, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('posh:import')->assertSuccessful();
    }

    /** Every address of the old poshmedica.co.th still answers, either with the page or a permanent redirect to it. */
    public function test_old_addresses_still_work(): void
    {
        $bad = [];
        foreach (file(base_path('tests/fixtures/old-urls.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $path) {
            $res = $this->get($path);
            $code = $res->getStatusCode();
            if ($code === 301 || $code === 302) {
                $to = parse_url($res->headers->get('Location'), PHP_URL_PATH);
                $code = $this->get(rawurldecode($to))->getStatusCode();
            }
            if ($code !== 200) $bad[] = "$code $path";
        }
        $this->assertSame([], $bad, "These old addresses no longer work:\n" . implode("\n", $bad));
    }

    public function test_bare_domain_goes_to_thai_home(): void
    {
        $this->get('/')->assertRedirect('/th/home');
        $this->get('/th')->assertStatus(301);
    }

    public function test_every_page_has_titles_canonical_and_both_languages(): void
    {
        $p = Product::first();
        foreach (['/th/home', '/en/home', '/th/about-us/corporate-info', '/en/our-business/services/oem-and-odm', '/th/contact-us', parse_url($p->url('en'), PHP_URL_PATH)] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertMatchesRegularExpression('#<title>[^<]{5,}</title>#u', $html, $url);
            $this->assertStringContainsString('rel="canonical"', $html, $url);
            $this->assertStringContainsString('hreflang="th"', $html, $url);
            $this->assertStringContainsString('hreflang="en"', $html, $url);
            $this->assertStringContainsString('application/ld+json', $html, $url);
            $this->assertStringNotContainsString('[home.', $html, "$url shows a missing page text");
        }
    }

    public function test_sitemap_and_robots(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')->getContent();
        $this->assertNotFalse(simplexml_load_string($xml));
        $this->assertStringContainsString('/th/home', $xml);
        $this->assertStringContainsString(Post::first()->url('th'), $xml);
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
    }

    public function test_staging_is_hidden_from_search_engines(): void
    {
        config(['site.noindex' => true]);
        $this->get('/robots.txt')->assertSee('Disallow: /');
        $this->get('/th/home')->assertSee('noindex,nofollow', false);
    }

    public function test_contact_form_saves_and_emails(): void
    {
        Mail::fake();
        $this->get('/th/contact-us');
        $this->post('/th/contact-us', ['topic' => 'order', 'name' => 'Somchai', 'email' => 'somchai@example.com', 'message' => 'สนใจสั่งซื้อ', 'consent' => '1'])
            ->assertRedirect();
        $this->assertSame(1, Enquiry::count());
        Mail::assertSent(NewEnquiry::class);
    }

    public function test_contact_form_needs_consent_and_ignores_bots(): void
    {
        $this->post('/en/contact-us', ['topic' => 'order', 'name' => 'A', 'email' => 'a@example.com', 'message' => 'x'])->assertSessionHasErrors('consent');
        $this->post('/en/contact-us', ['topic' => 'order', 'name' => 'A', 'email' => 'a@example.com', 'message' => 'x', 'consent' => '1', 'website' => 'spam.example'])->assertRedirect();
        $this->assertSame(0, Enquiry::count());
    }

    public function test_unpublished_products_are_hidden(): void
    {
        $p = Product::first(); $p->update(['is_published' => false]);
        $this->get(parse_url($p->url('th'), PHP_URL_PATH))->assertNotFound();
    }

    public function test_admin_screens_open(): void
    {
        config(['app.env' => 'testing']);
        $this->get('/admin/login')->assertOk();
        $u = User::factory()->create();
        foreach (['', '/page-texts', '/products', '/posts', '/awards', '/milestones', '/stats', '/stores', '/videos', '/policies', '/legal-pages', '/enquiries', '/redirects', '/settings', '/users'] as $s) {
            $this->actingAs($u)->get('/admin' . $s)->assertOk();
        }
        $this->actingAs($u)->get('/admin/products/' . Product::first()->id . '/edit')->assertOk();
        $this->actingAs($u)->get('/admin/posts/' . Post::first()->id . '/edit')->assertOk();
    }
}

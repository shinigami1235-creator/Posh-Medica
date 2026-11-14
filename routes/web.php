<?php

use App\Http\Controllers\{ContactController, LegalController, PageController, PolicyController, PostController, ProductController, SitemapController};
use Illuminate\Support\Facades\Route;

/*
 | The same addresses as the old poshmedica.co.th, so links, Google results and printed QR codes keep working.
 | Thai is the default language: / goes to /th/home.
 */
Route::get('/', fn () => redirect('/th/home', 301));
Route::get('sitemap.xml', [SitemapController::class, 'xml']);
Route::get('robots.txt', [SitemapController::class, 'robots']);

Route::prefix('{locale}')->where(['locale' => 'th|en'])->middleware('locale')->group(function () {
    Route::get('/', fn () => redirect(lroute('home'), 301));
    Route::get('home', [PageController::class, 'home'])->name('home');

    Route::get('about-us/corporate-info', [PageController::class, 'about'])->name('about');
    Route::get('about-us/company-milestone', PageController::to('about', '#milestone'));
    Route::get('about-us/message-from-company', PageController::to('about', '#message'));
    Route::get('about-us/organization-chart', PageController::to('about', '#org'));
    Route::get('about-us/awards-standards/awards', [PageController::class, 'awards'])->name('awards');
    Route::get('about-us/awards-standards/standards', PageController::to('awards', '#standards'));

    Route::get('our-business/products/product-overview', PageController::to('home', '#products'))->name('products');
    Route::get('products/supplements/{category}', PageController::to('home', '#products'))->where('category', '[a-z-]+-dietary-supplements');
    Route::get('products/supplements/{id}/{slug?}', [ProductController::class, 'show'])->whereNumber('id')->name('product');
    Route::get('our-business/services/oem-and-odm', [PageController::class, 'oem'])->name('oem');

    Route::get('investor-relations/corporate-governance/{policy}', [PolicyController::class, 'show'])->name('policy');
    Route::get('investor-relations', fn () => redirect(lroute('ir'), 301));
    Route::get('investor-relations/corporate-governance', fn () => redirect(lroute('ir'), 301))->name('ir.base');
    Route::get('investor-relations/corporate-governance/corporate-governance-policy', [PolicyController::class, 'show'])->defaults('policy', 'corporate-governance-policy')->name('ir');

    Route::get('updates/blog-and-event/event', [PostController::class, 'index'])->defaults('kind', 'event')->name('events');
    Route::get('updates/blog-and-event/blog', [PostController::class, 'index'])->defaults('kind', 'blog')->name('blog');
    Route::get('updates/blog-and-event', fn () => redirect(lroute('events'), 301));
    Route::get('updates/blog-and-event/{kind}/{id}/{slug?}', [PostController::class, 'show'])->whereIn('kind', ['event', 'blog'])->whereNumber('id')->name('post');

    Route::get('contact-us', [ContactController::class, 'show'])->name('contact');
    Route::post('contact-us', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.send');

    Route::get('sitemap', [LegalController::class, 'sitemap'])->name('sitemap');
    Route::get('{page}', [LegalController::class, 'show'])->whereIn('page', ['privacy-policy', 'terms-conditions', 'cookie-policy'])->name('legal');
});

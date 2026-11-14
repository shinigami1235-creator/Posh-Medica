<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* Every table the website reads. Text that shows on the site has an English (_en) and a Thai (_th) column. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_texts', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->string('page')->index();
            $t->text('th')->nullable();
            $t->text('en')->nullable();
            $t->boolean('is_html')->default(false);
            $t->timestamps();
        });
        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->text('value')->nullable();
            $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('group')->index();          // fiber, care, skin, pung
            $t->string('form')->index();           // powder, capsule, jelly
            $t->string('symbol', 4);
            $t->string('name_th'); $t->string('name_en');
            $t->string('slug_th'); $t->string('slug_en');
            $t->text('desc_th')->nullable(); $t->text('desc_en')->nullable();
            $t->json('ingredients_th')->nullable(); $t->json('ingredients_en')->nullable();
            $t->json('badges')->nullable();
            $t->string('image')->nullable();       // the pack shot on the product table
            $t->json('photos')->nullable();        // the gallery on the product page
            $t->string('seo_title_th')->nullable(); $t->string('seo_title_en')->nullable();
            $t->string('seo_description_th', 320)->nullable(); $t->string('seo_description_en', 320)->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('kind')->index();           // event, blog
            $t->string('slug');
            $t->date('published_on')->index();
            $t->string('title_th'); $t->string('title_en')->nullable();
            $t->longText('body_th')->nullable(); $t->longText('body_en')->nullable();
            $t->string('cover')->nullable();
            $t->json('gallery')->nullable();
            $t->string('seo_description_th', 320)->nullable(); $t->string('seo_description_en', 320)->nullable();
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
        Schema::create('awards', function (Blueprint $t) {
            $t->id();
            $t->string('kind')->index();           // award, standard
            $t->string('title_th'); $t->string('title_en');
            $t->string('image')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
        Schema::create('milestones', function (Blueprint $t) {
            $t->id();
            $t->string('year', 8);
            $t->string('title_th'); $t->string('title_en');
            $t->text('text_th')->nullable(); $t->text('text_en')->nullable();
            $t->string('image')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
        Schema::create('stats', function (Blueprint $t) {
            $t->id();
            $t->string('label_th'); $t->string('label_en');
            $t->string('value');
            $t->string('unit_th')->nullable(); $t->string('unit_en')->nullable();
            $t->decimal('fill', 3, 2)->default(0.7);   // how full the test tube is, 0 to 1
            $t->string('color')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });
        Schema::create('stores', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('logo')->nullable();
            $t->string('url')->nullable();
            $t->unsignedTinyInteger('shelf')->default(1);
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
        Schema::create('videos', function (Blueprint $t) {
            $t->id();
            $t->string('youtube_id', 20);
            $t->string('title_th')->nullable(); $t->string('title_en')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
        Schema::create('policies', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title_th'); $t->string('title_en');
            $t->longText('body_th')->nullable(); $t->longText('body_en')->nullable();
            $t->json('documents')->nullable();      // [{title_th, title_en, file}]
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });
        Schema::create('legal_pages', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();           // privacy-policy, terms-conditions, cookie-policy
            $t->string('title_th'); $t->string('title_en');
            $t->longText('body_th')->nullable(); $t->longText('body_en')->nullable();
            $t->timestamps();
        });
        Schema::create('enquiries', function (Blueprint $t) {
            $t->id();
            $t->string('topic');
            $t->string('name');
            $t->string('email');
            $t->string('phone')->nullable();
            $t->text('message');
            $t->string('attachment')->nullable();
            $t->string('locale', 2)->default('th');
            $t->timestamp('consented_at')->nullable();
            $t->string('ip_hash', 64)->nullable();
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
        });
        Schema::create('redirects', function (Blueprint $t) {
            $t->id();
            $t->string('from_path')->unique();
            $t->string('to_path');
            $t->unsignedSmallInteger('code')->default(301);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['redirects', 'enquiries', 'legal_pages', 'policies', 'videos', 'stores', 'stats', 'milestones', 'awards', 'posts', 'products', 'settings', 'page_texts'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};

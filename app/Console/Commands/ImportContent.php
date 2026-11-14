<?php

namespace App\Console\Commands;

use App\Models\{Award, LegalPage, Milestone, PageText, Policy, Post, Product, Setting, Stat, Store, Video};
use App\Support\{Settings, Texts};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Loads the site's content (both languages) and its photos into the database and storage.
 * Safe to run again: each item is matched by its key or id and updated in place.
 * --fresh empties the content tables first. --keep-edits leaves page texts that someone already changed in the admin alone.
 */
class ImportContent extends Command
{
    protected $signature = 'posh:import {--fresh : Empty the content tables first} {--keep-edits : Leave page texts that were edited in the admin}';
    protected $description = 'Import the Posh Medica content and photos from database/content';

    public function handle(): int
    {
        $dir = database_path('content');
        $j = fn ($f) => json_decode(File::get("$dir/$f.json"), true);
        if ($this->option('fresh')) {
            foreach ([PageText::class, Product::class, Post::class, Award::class, Milestone::class, Stat::class, Store::class, Video::class, Policy::class, LegalPage::class] as $m) $m::query()->delete();
        }

        // photos: our own crops go under storage/app/public/site, files from the old site keep their old /storage addresses
        $pub = storage_path('app/public');
        foreach (['products', 'awards', 'partners', 'milestones'] as $d) {
            if (File::isDirectory("$dir/media/$d")) File::copyDirectory("$dir/media/$d", "$pub/site/$d");
        }
        if (File::isDirectory("$dir/media/storage")) File::copyDirectory("$dir/media/storage", $pub);

        $texts = array_merge($j('page_texts'), $j('page_texts_extra'));
        $kept = 0;
        foreach ($texts as $t) {
            $row = PageText::firstOrNew(['key' => $t['key']]);
            if ($this->option('keep-edits') && $row->exists && $row->updated_at > $row->created_at) { $kept++; continue; }
            $row->fill(['page' => $t['page'], 'en' => $t['en'], 'th' => $t['th'], 'is_html' => $t['html']])->save();
        }
        foreach ($j('products') as $p) Product::updateOrCreate(['id' => $p['id']], $p + ['is_published' => true]);
        foreach ($j('posts') as $p) Post::updateOrCreate(['id' => $p['id']], $p + ['is_published' => true]);
        foreach ($j('policies') as $p) Policy::updateOrCreate(['slug' => $p['slug']], $p);
        foreach ($j('legal_pages') as $p) LegalPage::updateOrCreate(['slug' => $p['slug']], $p);
        foreach ([['awards', Award::class, ['kind', 'title_en']], ['milestones', Milestone::class, ['year']], ['stats', Stat::class, ['label_en']], ['stores', Store::class, ['name']], ['videos', Video::class, ['youtube_id']]] as [$f, $m, $keys]) {
            foreach ($j($f) as $row) $m::updateOrCreate(array_intersect_key($row, array_flip($keys)), $row);
        }
        foreach ($j('settings') as $k => $v) Setting::firstOrCreate(['key' => $k], ['value' => $v]);   // never overwrite a setting changed in the admin
        Texts::forget(); Settings::forget();

        $this->info(sprintf('Imported %d page texts%s, %d products, %d posts, %d policies, %d awards, %d milestones, %d stats, %d stores, %d videos.',
            count($texts) - $kept, $kept ? " ($kept edited ones kept)" : '', Product::count(), Post::count(), Policy::count(), Award::count(), Milestone::count(), Stat::count(), Store::count(), Video::count()));
        $missing = collect(Post::pluck('cover'))->merge(Product::pluck('image'))->filter()->reject(fn ($p) => File::exists("$pub/$p"));
        if ($missing->isNotEmpty()) $this->warn($missing->count() . ' photos are not in storage yet. Copy the old site\'s storage/app/public folder across (see HANDOVER.md, step 5).');
        return self::SUCCESS;
    }
}

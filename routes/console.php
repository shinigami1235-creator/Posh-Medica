<?php

use App\Models\Enquiry;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

/* Contact form messages are kept for config('site.enquiry_days') days, then deleted with their attachments (PDPA). Needs the scheduler: see HANDOVER.md. */
Schedule::call(function () {
    Enquiry::where('created_at', '<', now()->subDays((int) config('site.enquiry_days')))->get()->each(function ($e) {
        if ($e->attachment) Storage::disk('local')->delete($e->attachment);
        $e->delete();
    });
})->daily()->name('prune-enquiries')->withoutOverlapping();

<?php

namespace App\Http\Controllers;

use App\Mail\NewEnquiry;
use App\Models\Enquiry;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public const TOPICS = ['order', 'issue', 'job', 'info'];

    public function show()
    {
        return view('pages.contact', [
            'seo' => Seo::make(['title' => t('meta.contact-title'), 'description' => t('meta.contact-description'), 'schema' => [[
                '@type' => 'ContactPage', 'name' => t('meta.contact-title'), 'url' => url()->current()]]]),
            'accent' => ['#8cbf26', '#ecf7c9', '#4f6b12'], 'js' => ['contact'],
        ]);
    }

    public function store(Request $request)
    {
        // a hidden field people never see; bots fill it in
        if (filled($request->input('website'))) return redirect(lroute('contact'))->with('sent', true);
        $data = $request->validate([
            'topic' => ['required', 'in:' . implode(',', self::TOPICS)],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:4000'],
            'file' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'consent' => ['accepted'],
        ]);
        $path = $request->file('file')?->store('enquiries/' . now()->format('Y/m'), 'local');   // private, never served directly
        $e = Enquiry::create([
            'topic' => $data['topic'], 'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'] ?? null,
            'message' => $data['message'], 'attachment' => $path, 'locale' => app()->getLocale(),
            'consented_at' => now(), 'ip_hash' => hash('sha256', $request->ip() . config('app.key')),
        ]);
        try {
            Mail::to(setting('contact_to', config('site.contact_to')))->send(new NewEnquiry($e));
        } catch (\Throwable $ex) {
            Log::error('Contact email failed: ' . $ex->getMessage());   // the message is still saved under Enquiries
        }
        return redirect(lroute('contact') . '#form')->with('sent', true);
    }
}

<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class NewEnquiry extends Mailable
{
    public function __construct(public Enquiry $e) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Website message: ' . __('posh.topics.' . $this->e->topic, [], 'th') . ' / ' . $this->e->name,
            replyTo: [new Address($this->e->email, $this->e->name)]);
    }

    public function content(): Content
    {
        return new Content(text: 'mail.enquiry');
    }
}

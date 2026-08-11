<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** İletişim formunu dolduran ZİYARETÇİYE onay maili — kendi dilinde. */
class ContactConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
        $this->locale($contactMessage->locale ?: config('app.fallback_locale'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.contact.subject', [], $this->contactMessage->locale),
            replyTo: setting('eposta') ? [setting('eposta')] : [],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-confirmation');
    }
}

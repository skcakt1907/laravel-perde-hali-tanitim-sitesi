<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Ölçü talebi bırakan ZİYARETÇİYE onay maili — talebi hangi dilde bıraktıysa o dilde.
 * (İşletmeye giden bildirim ayrı: AufmassRequested, Türkçe.)
 */
class AufmassConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
        // Mailable'ın çevirileri ziyaretçinin dilinden yapılsın
        $this->locale($appointment->locale ?: config('app.fallback_locale'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.aufmass.subject', [], $this->appointment->locale),
            replyTo: setting('eposta') ? [setting('eposta')] : [],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.aufmass-confirmation');
    }
}

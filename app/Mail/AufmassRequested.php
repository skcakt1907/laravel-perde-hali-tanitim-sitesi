<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Yeni ücretsiz ölçü talebi — işletmeye bildirim (panel dili: Türkçe) */
class AufmassRequested extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yeni ölçü talebi: ' . $this->appointment->name,
            replyTo: $this->appointment->email ? [$this->appointment->email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.aufmass-requested');
    }
}

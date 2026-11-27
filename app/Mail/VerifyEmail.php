<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Sign-up email: a 6-digit code (for the app or the website) and a one-click link. */
class VerifyEmail extends Mailable
{
    use Queueable;

    public function __construct(
        public string $name,
        public string $code,
        public string $link,
        public int $minutes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Your Panobooking verification code: {$this->code}");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.verify-email');
    }
}

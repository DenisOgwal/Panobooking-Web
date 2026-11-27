<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Password reset: a 6-digit code entered in the app or on the website. */
class ResetPasswordCode extends Mailable
{
    use Queueable;

    public function __construct(
        public string $name,
        public string $code,
        public int $minutes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset your Panobooking password');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.reset-password');
    }
}

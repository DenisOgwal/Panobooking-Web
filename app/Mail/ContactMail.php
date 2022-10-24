<?php

namespace App\Mail;

use App\Http\Livewire\ContactPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public  ContactPage $ContactPage; 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ContactPage)
    {
        $this->ContactPage=$ContactPage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contact Inquiry')->view('mails.contact-mail');
    }
}
 
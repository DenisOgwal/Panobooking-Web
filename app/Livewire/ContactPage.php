<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Attributes\Validate;
use Livewire\Component;

/** /contactus: contact details and an enquiry form (stored in the `messages` table). */
class ContactPage extends Component
{
    #[Validate('required|min:3|max:120', as: 'name')]
    public string $inputname = '';

    #[Validate('required|email|max:190', as: 'email')]
    public string $email = '';

    #[Validate('required|min:20|max:5000', as: 'message')]
    public string $contactinfo = '';

    public function sendmessage(): void
    {
        $this->validate();

        Contact::create([
            'Names' => $this->inputname,
            'Email' => $this->email,
            'Messages' => $this->contactinfo,
        ]);

        $this->reset();
        $this->dispatch('alert', type: 'success', message: 'Thanks for getting in touch! We will reply as soon as we can.');
    }

    public function render()
    {
        return view('livewire.contact-page')->layout('components.layouts.app', ['title' => 'Contact us']);
    }
}

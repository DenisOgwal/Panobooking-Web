<?php

namespace App\Livewire;

use App\Models\BlogModel;
use App\Models\DestinationsModel;
use App\Models\events as EventModel;
use App\Models\FeedBackModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\RoomsModel;
use App\Models\TourismSite;
use App\Support\HomeContent;
use Livewire\Attributes\Validate;
use Livewire\Component;

class HomePage extends Component
{
    // "Share your experience" form
    #[Validate('required|min:3', as: 'name')]
    public $clientname = '';

    #[Validate('required|email', as: 'email')]
    public $clientemail = '';

    public $clientjob = '';

    #[Validate('required', as: 'company')]
    public $clientcompany = '';

    #[Validate('required|min:20', as: 'review')]
    public $clientfeedback = '';

    public function submitfeedback(): void
    {
        $this->validate();

        FeedBackModel::create([
            'ClientName' => $this->clientname,
            'ClientEmail' => $this->clientemail,
            'ClientJob' => $this->clientjob,
            'ClientCompany' => $this->clientcompany,
            'FeedBack' => $this->clientfeedback,
        ]);

        $this->reset('clientname', 'clientemail', 'clientjob', 'clientcompany', 'clientfeedback');
        $this->dispatch('alert', type: 'success', message: 'Thanks! Your feedback will appear once it has been reviewed.');
    }

    /** Page content changes rarely; cache it so form interactions don't re-run every query. */
    private function content(): array
    {
        return HomeContent::get();
    }

    public function render()
    {
        return view('livewire.home-page', $this->content())
            ->layout('components.layouts.app');
    }
}

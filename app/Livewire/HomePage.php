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
use Illuminate\Support\Facades\Cache;
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
        return Cache::remember('home.content', 300, function () {
            $hotelCounts = HotelsModel::approved()->selectRaw('City, COUNT(*) as total')->groupBy('City')->pluck('total', 'City');

            return [
                'offer' => RoomsModel::offered()->with('hotel:IDs,Place,City')->orderByDesc('IDs')->first(),
                'trending' => HotelsModel::approved()->trending()->orderBy('TrendRank')->limit(12)->get(),
                'featured' => HotelsModel::approved()->featured()->orderBy('FaturedRank')->limit(12)->get(),
                'destinations' => DestinationsModel::orderBy('DestinationID')->limit(8)->get()
                    ->each(fn ($d) => $d->setAttribute('stays', (int) ($hotelCounts[$d->City] ?? 0))),
                'sites' => TourismSite::orderBy('ID')->limit(8)->get(),
                'events' => EventModel::upcoming()->orderBy('StartDate')->limit(6)->get(),
                'articles' => BlogModel::withCount('approvedComments')->orderByDesc('id')->limit(3)->get(),
                'testimonials' => FeedBackModel::approved()->orderByDesc('id')->limit(6)->get(),
                'stats' => [
                    'stays' => HotelsModel::approved()->count(),
                    'cars' => RentalCarModel::approved()->count(),
                    'cities' => $hotelCounts->count(),
                ],
            ];
        });
    }

    public function render()
    {
        return view('livewire.home-page', $this->content())
            ->layout('components.layouts.app');
    }
}

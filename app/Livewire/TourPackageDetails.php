<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesBookingType;
use App\Models\TourPackages as TourPackageModel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /TourPackageDetails?searchslug={IDs}: tour package with reservation (rules: App\Bookings\Types\TourBooking). */
class TourPackageDetails extends Component
{
    use UsesBookingType;

    #[Url(as: 'searchslug')]
    public $url_searchslug;

    public $travellers = 1;

    protected function bookingKey(): string { return 'tour'; }

    protected function bookingFields(): array { return ['travellers' => 'travellers']; }

    protected function bookable(): ?Model { return $this->package; }

    #[Computed]
    public function package(): ?TourPackageModel
    {
        return $this->bookingType()->find($this->url_searchslug);
    }

    #[Computed]
    public function total(): float
    {
        return $this->bookingTotal();
    }

    public function updatedTravellers(): void
    {
        $this->validateOnly('travellers');
    }

    public function submitbooking()
    {
        return $this->placeBooking('Reservation received!');
    }

    public function render()
    {
        $p = $this->package;
        abort_if(! $p, 404);

        return view('livewire.tour-package-details', [
            'p' => $p,
            'gallery' => \App\Support\Media::gallery('tour', $p, 9),
            'more' => TourPackageModel::upcoming()->where('IDs', '!=', $p->IDs)->orderBy('TravelDate')->limit(8)->get(),
        ])->layout('components.layouts.app', ['title' => $p->PackageName]);
    }
}

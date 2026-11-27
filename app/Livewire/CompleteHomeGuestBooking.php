<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesBookingType;
use App\Models\HomeGuestModel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /CompleteHomeGuestBooking?homeguestfinalslug={IDs}: book a whole guest house (rules: App\Bookings\Types\HomeStayBooking). */
class CompleteHomeGuestBooking extends Component
{
    use UsesBookingType;

    #[Url(as: 'homeguestfinalslug')]
    public $url_homeguestfinalslug;

    public string $checkindate = '';
    public string $checkoutdate = '';
    public $Childrenno = 0;
    public string $BookingDescription = '';

    protected function bookingKey(): string { return 'home-stay'; }

    protected function bookingFields(): array
    {
        return ['check_in' => 'checkindate', 'check_out' => 'checkoutdate', 'children' => 'Childrenno', 'notes' => 'BookingDescription'];
    }

    protected function bookable(): ?Model { return $this->house; }

    public function mount(): void
    {
        abort_if(! $this->house, 404);
        $this->applyBookingDefaults();
    }

    #[Computed]
    public function house(): ?HomeGuestModel
    {
        return $this->bookingType()->find($this->url_homeguestfinalslug);
    }

    #[Computed]
    public function nights(): int
    {
        return (int) ($this->bookingType()->breakdown($this->house, $this->bookingData())['nights'] ?? 0);
    }

    #[Computed]
    public function total(): float
    {
        return $this->bookingTotal();
    }

    public function updated($field): void
    {
        $this->validateOnly($field);
    }

    public function submitfeedback()
    {
        return $this->placeBooking('Booking received!');
    }

    public function render()
    {
        return view('livewire.complete-home-guest-booking')
            ->layout('components.layouts.app', ['title' => 'Book ' . $this->house->GuestHouseName]);
    }
}

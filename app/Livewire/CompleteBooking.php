<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesBookingType;
use App\Models\RoomsModel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /CompleteBooking?hotelfinalslug={room IDs}: reserve a hotel room (rules: App\Bookings\Types\RoomBooking). */
class CompleteBooking extends Component
{
    use UsesBookingType;

    #[Url(as: 'hotelfinalslug')]
    public $url_hotelfinalslug;

    public string $checkindate = '';
    public string $checkoutdate = '';
    public $Destinationquantity = 1;
    public $Childrenno = 0;

    protected function bookingKey(): string { return 'room'; }

    protected function bookingFields(): array
    {
        return ['check_in' => 'checkindate', 'check_out' => 'checkoutdate', 'rooms' => 'Destinationquantity', 'children' => 'Childrenno'];
    }

    protected function bookable(): ?Model { return $this->room; }

    public function mount(): void
    {
        abort_if(! $this->room, 404);
        $this->applyBookingDefaults();
    }

    #[Computed]
    public function room(): ?RoomsModel
    {
        return $this->bookingType()->find($this->url_hotelfinalslug);
    }

    #[Computed]
    public function nights(): int
    {
        return (int) ($this->bookingType()->breakdown($this->room, $this->bookingData())['nights'] ?? 0);
    }

    /** Total in UGX. */
    #[Computed]
    public function total(): float
    {
        return $this->bookingTotal();
    }

    public function updated($field): void
    {
        $this->validateOnly($field);
        if ($field === 'checkindate') {
            $this->resetValidation('checkoutdate');
        }
    }

    public function submitfeedback()
    {
        return $this->placeBooking('Booking received!');
    }

    public function render()
    {
        return view('livewire.complete-booking')
            ->layout('components.layouts.app', ['title' => 'Reserve ' . $this->room->RoomName]);
    }
}

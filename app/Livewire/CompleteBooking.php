<?php

namespace App\Livewire;

use App\Livewire\Concerns\SignsInInline;
use App\Models\CartModel;
use App\Models\RoomsModel;
use App\Support\Currency;
use App\Support\CustomerAuth;
use App\Support\ProviderNotifier;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /CompleteBooking?hotelfinalslug={room IDs}: reserve a hotel room. */
class CompleteBooking extends Component
{
    use SignsInInline;

    #[Url(as: 'hotelfinalslug')]
    public $url_hotelfinalslug;

    public string $checkindate = '';
    public string $checkoutdate = '';
    public $Destinationquantity = 1;
    public $Childrenno = 0;

    public function mount(): void
    {
        abort_if(! $this->room, 404);
        $this->checkindate = now()->addDay()->toDateString();
        $this->checkoutdate = now()->addDays(2)->toDateString();
    }

    #[Computed]
    public function room(): ?RoomsModel
    {
        return RoomsModel::with(['roomdetails', 'hotel'])->find($this->url_hotelfinalslug);
    }

    #[Computed]
    public function nights(): int
    {
        try {
            $nights = (int) Carbon::parse($this->checkindate)->diffInDays(Carbon::parse($this->checkoutdate), false);
        } catch (\Throwable) {
            return 0;
        }

        return max(0, $nights);
    }

    /** Total in UGX. */
    #[Computed]
    public function total(): float
    {
        return $this->nights * max(0, (int) $this->Destinationquantity) * (float) $this->room?->Price;
    }

    protected function rules(): array
    {
        $available = max(1, (int) $this->room?->NoOfRooms);

        return [
            'checkindate' => 'required|date|after_or_equal:today',
            'checkoutdate' => 'required|date|after:checkindate',
            'Destinationquantity' => "required|integer|min:1|max:{$available}",
            'Childrenno' => 'nullable|integer|min:0|max:20',
        ];
    }

    protected function validationAttributes(): array
    {
        return ['checkindate' => 'check-in', 'checkoutdate' => 'check-out', 'Destinationquantity' => 'rooms', 'Childrenno' => 'children'];
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
        $auth = app(CustomerAuth::class);
        if (! $auth->check()) {
            $this->addError('clientemail', 'Please sign in to complete your booking.');

            return;
        }

        $this->validate();

        $room = $this->room;
        $currency = app(Currency::class);
        $children = (int) $this->Childrenno;

        CartModel::create([
            'Facility' => $room->HotelName,
            'ProductName' => $room->RoomName,
            'User' => $auth->email(),
            'UserNames' => $auth->name(),
            'Quantity' => (int) $this->Destinationquantity,
            'Cost' => $this->total,
            'OrderCode' => 'N/A',
            'DatesFrom' => Carbon::parse($this->checkindate)->toDateString(),
            'DatesTo' => Carbon::parse($this->checkoutdate)->toDateString(),
            'BookingType' => 'Stays',
            'Currency' => $currency->code(),
            'CurrencyAmount' => $currency->convert($this->total),
            'Descriptions' => "Has {$children} Children",
        ]);

        app(ProviderNotifier::class)->send('SendHotel.php', [
            'hotelemail' => $room->hotel?->EmailAddress,
            'bookingnotes' => sprintf('Please reserve for %s %d %s room(s) from %s to %s. TOTAL PAYABLE: %s. Client has %d children.',
                $auth->name(), $this->Destinationquantity, $room->RoomName, $this->checkindate, $this->checkoutdate,
                $currency->format($this->total), $children),
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => 'Booking received!']);

        return $this->redirect('/BookingSuccess');
    }

    public function render()
    {
        return view('livewire.complete-booking')
            ->layout('components.layouts.app', ['title' => 'Reserve ' . $this->room->RoomName]);
    }
}

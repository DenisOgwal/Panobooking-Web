<?php

namespace App\Livewire;

use App\Livewire\Concerns\SignsInInline;
use App\Models\CartModel;
use App\Models\HomeGuestModel;
use App\Support\Currency;
use App\Support\CustomerAuth;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /CompleteHomeGuestBooking?homeguestfinalslug={IDs}: book a whole guest house. */
class CompleteHomeGuestBooking extends Component
{
    use SignsInInline;

    #[Url(as: 'homeguestfinalslug')]
    public $url_homeguestfinalslug;

    public string $checkindate = '';
    public string $checkoutdate = '';
    public $Childrenno = 0;
    public string $BookingDescription = '';

    public function mount(): void
    {
        abort_if(! $this->house, 404);
        $this->checkindate = now()->addDay()->toDateString();
        $this->checkoutdate = now()->addDays(2)->toDateString();
    }

    #[Computed]
    public function house(): ?HomeGuestModel
    {
        return HomeGuestModel::with('houserulesdetail')->find($this->url_homeguestfinalslug);
    }

    #[Computed]
    public function nights(): int
    {
        try {
            return max(0, (int) Carbon::parse($this->checkindate)->diffInDays(Carbon::parse($this->checkoutdate), false));
        } catch (\Throwable) {
            return 0;
        }
    }

    #[Computed]
    public function total(): float
    {
        return $this->nights * (float) $this->house?->Prices;
    }

    protected function rules(): array
    {
        return [
            'checkindate' => 'required|date|after_or_equal:today',
            'checkoutdate' => 'required|date|after:checkindate',
            'Childrenno' => 'nullable|integer|min:0|max:20',
            'BookingDescription' => 'required|string|min:3|max:1000',
        ];
    }

    protected function validationAttributes(): array
    {
        return ['checkindate' => 'check-in', 'checkoutdate' => 'check-out', 'Childrenno' => 'children', 'BookingDescription' => 'notes for your host'];
    }

    public function updated($field): void
    {
        $this->validateOnly($field);
    }

    public function submitfeedback()
    {
        $auth = app(CustomerAuth::class);
        if (! $auth->check()) {
            $this->addError('clientemail', 'Please sign in to complete your booking.');

            return;
        }

        $this->validate();
        $currency = app(Currency::class);

        CartModel::create([
            'Facility' => $this->house->GuestHouseName,
            'ProductName' => $this->house->GuestHouseName,
            'User' => $auth->email(),
            'UserNames' => $auth->name(),
            'Quantity' => 1,
            'Cost' => $this->total,
            'OrderCode' => 'N/A',
            'DatesFrom' => Carbon::parse($this->checkindate)->toDateString(),
            'DatesTo' => Carbon::parse($this->checkoutdate)->toDateString(),
            'BookingType' => 'Home Guest House',
            'Currency' => $currency->code(),
            'CurrencyAmount' => $currency->convert($this->total),
            'Descriptions' => 'Has ' . (int) $this->Childrenno . ' Children, ' . $this->BookingDescription,
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => 'Booking received!']);

        return $this->redirect('/BookingSuccess');
    }

    public function render()
    {
        return view('livewire.complete-home-guest-booking')
            ->layout('components.layouts.app', ['title' => 'Book ' . $this->house->GuestHouseName]);
    }
}

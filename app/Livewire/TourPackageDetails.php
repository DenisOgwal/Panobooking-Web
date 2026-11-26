<?php

namespace App\Livewire;

use App\Livewire\Concerns\SignsInInline;
use App\Models\CartModel;
use App\Models\TourPackages as TourPackageModel;
use App\Support\Currency;
use App\Support\CustomerAuth;
use App\Support\ProviderNotifier;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /TourPackageDetails?searchslug={IDs}: tour package with reservation. */
class TourPackageDetails extends Component
{
    use SignsInInline;

    #[Url(as: 'searchslug')]
    public $url_searchslug;

    public $travellers = 1;

    #[Computed]
    public function package(): ?TourPackageModel
    {
        return TourPackageModel::find($this->url_searchslug);
    }

    #[Computed]
    public function total(): float
    {
        return (float) $this->package?->PackageFees2 * max(0, (int) $this->travellers);
    }

    public function updatedTravellers(): void
    {
        $this->validateOnly('travellers', ['travellers' => 'required|integer|min:1|max:50'], [], ['travellers' => 'travellers']);
    }

    public function submitbooking()
    {
        $auth = app(CustomerAuth::class);
        if (! $auth->check()) {
            $this->addError('clientemail', 'Please sign in to reserve this package.');

            return;
        }
        $this->validate(['travellers' => 'required|integer|min:1|max:50'], [], ['travellers' => 'travellers']);

        $p = $this->package;
        abort_if(! $p, 404);
        $currency = app(Currency::class);

        CartModel::create([
            'Facility' => $p->TravelCompany,
            'ProductName' => $p->PackageName,
            'User' => $auth->email(),
            'UserNames' => $auth->name(),
            'Quantity' => (int) $this->travellers,
            'Cost' => $this->total,
            'OrderCode' => 'N/A',
            'DatesFrom' => $p->TravelDate ?: 'N/A',
            'DatesTo' => 'N/A',
            'BookingType' => 'Tour Package',
            'Currency' => $currency->code(),
            'CurrencyAmount' => $currency->convert($this->total),
            'Descriptions' => 'Please Reserve ' . $p->PackageName . ' Package for ' . $auth->name() . ' (' . (int) $this->travellers . ' travellers)',
        ]);

        app(ProviderNotifier::class)->send('SendPackage.php', [
            'packageemail' => $p->EmailAddress,
            'bookingnotes' => sprintf('Please reserve %s for %s, %d traveller(s). TOTAL: %s', $p->PackageName, $auth->name(), $this->travellers, $currency->format($this->total)),
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => 'Reservation received!']);

        return $this->redirect('/BookingSuccess');
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

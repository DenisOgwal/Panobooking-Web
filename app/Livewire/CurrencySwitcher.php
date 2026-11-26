<?php

namespace App\Livewire;

use App\Support\Currency;
use Livewire\Component;

/**
 * Header currency picker. Works for guests (session only) and saves the choice for
 * logged-in customers. Reloads the current page so every price re-renders.
 */
class CurrencySwitcher extends Component
{
    public string $code = Currency::BASE;

    /** URL of the page the switcher was rendered on (Livewire updates post to its own endpoint). */
    public string $returnTo = '/';

    public function mount(Currency $currency): void
    {
        $this->code = $currency->code();
        $this->returnTo = request()->fullUrl();
    }

    public function updatedCode(Currency $currency)
    {
        $currency->set($this->code);

        return $this->redirect($this->returnTo);
    }

    public function render(Currency $currency)
    {
        return view('livewire.currency-switcher', ['codes' => $currency->available()]);
    }
}

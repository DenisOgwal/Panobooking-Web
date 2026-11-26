<?php

namespace App\Livewire;

/** /TermsAndConditions */
class TermsAndConditionsComponent extends InfoPage
{
    protected function page(): array
    {
        return ['Terms and conditions', 'The marketplace terms that apply when you book through Panobooking.', 'terms', 'bi-file-text'];
    }
}

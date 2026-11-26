<?php

namespace App\Livewire;

/** /privacypolicy */
class PrivacyPolicyPage extends InfoPage
{
    protected function page(): array
    {
        return ['Privacy policy', 'How we collect, use, share and protect your information.', 'privacy-policy', 'bi-shield-lock'];
    }
}

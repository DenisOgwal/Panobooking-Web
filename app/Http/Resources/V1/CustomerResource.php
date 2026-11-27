<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** The signed-in customer (never the password or codes). @mixin \App\Models\UserRegistrationModel */
class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->FullNames,
            'email' => $this->Email,
            'phone' => $this->Telephone,
            'currency' => $this->SelectedCurrency ?: 'UGX',
            'email_verified' => $this->email_verified_at !== null,
            'member_since' => $this->created_at?->toDateString(),
        ];
    }
}

<?php

namespace App\Http\Resources\V1;

use App\Models\CartModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * One of the signed-in customer's bookings (`cart`).
 * status: pending | confirmed | declined. Cost is in UGX; `charged` is what the customer saw at booking time.
 *
 * @mixin \App\Models\CartModel
 */
class BookingResource extends JsonResource
{
    public const TYPES = [
        'Stays' => 'room', 'Home Guest House' => 'home-stay', 'Car Rental' => 'car',
        'Airport Taxi' => 'taxi', 'Ambulance' => 'ambulance', 'Tour Package' => 'tour',
    ];

    public function toArray(Request $request): array
    {
        $from = $this->DatesFrom && $this->DatesFrom !== 'N/A' ? (string) Str::of($this->DatesFrom)->before(' 00:00:00') : null;
        $to = $this->DatesTo && $this->DatesTo !== 'N/A' ? (string) Str::of($this->DatesTo)->before(' 00:00:00') : null;

        return [
            'id' => $this->IDs,
            'type' => self::TYPES[$this->BookingType] ?? 'room',
            'type_label' => $this->BookingType ?: 'Stay',
            'status' => strtolower((string) ($this->Confirmed ?: CartModel::PENDING)),
            'provider_note' => $this->ConfirmReason,
            'facility' => $this->Facility,
            'item' => $this->ProductName,
            'quantity' => (int) ($this->Quantity ?: 1),
            'from' => $from,
            'to' => $to,
            'details' => $this->Descriptions,
            'cost' => (float) $this->Cost > 0 ? Present::price($this->Cost) : null,
            'charged' => $this->Currency ? ['amount' => (float) $this->CurrencyAmount, 'currency' => $this->Currency] : null,
            'can_cancel' => $this->Confirmed === CartModel::PENDING,
            'requested_at' => $this->created_at?->toIso8601String() ?? ($this->Date ? (string) $this->Date : null),
        ];
    }
}

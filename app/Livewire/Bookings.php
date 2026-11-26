<?php

namespace App\Livewire;

use App\Livewire\Concerns\ListsCustomerBookings;
use Livewire\Component;

/** /Bookings: the customer's confirmed bookings. */
class Bookings extends Component
{
    use ListsCustomerBookings;

    public function render()
    {
        return view('livewire.bookings-list', [
            'bookings' => $this->customerBookings()->confirmed()->paginate(10),
            'pending' => false,
            'title' => 'Booking history',
            'subtitle' => 'Bookings confirmed by the provider.',
        ])->layout('components.layouts.app', ['title' => 'Booking history']);
    }
}

<?php

namespace App\Livewire;

use App\Livewire\Concerns\ListsCustomerBookings;
use Livewire\Component;

/** /Bookings: the customer's answered bookings (confirmed or declined by the provider). */
class Bookings extends Component
{
    use ListsCustomerBookings;

    public function render()
    {
        return view('livewire.bookings-list', [
            'bookings' => $this->customerBookings()->decided()->paginate(10),
            'pending' => false,
            'title' => 'Booking history',
            'subtitle' => 'Bookings the provider has confirmed or declined.',
        ])->layout('components.layouts.app', ['title' => 'Booking history']);
    }
}

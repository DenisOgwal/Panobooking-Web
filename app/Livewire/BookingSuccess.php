<?php

namespace App\Livewire;

use Livewire\Component;

class BookingSuccess extends Component
{
    public function render()
    {
        return view('livewire.booking-success')
            ->layout('components.layouts.app', ['title' => 'Booking received']);
    }
}

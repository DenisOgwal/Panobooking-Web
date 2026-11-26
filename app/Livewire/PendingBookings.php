<?php

namespace App\Livewire;

use App\Livewire\Concerns\ListsCustomerBookings;
use App\Models\CartModel;
use App\Support\CustomerAuth;
use Livewire\Component;

/** /PendingBookings: the customer's bookings awaiting confirmation (cancellable). */
class PendingBookings extends Component
{
    use ListsCustomerBookings;

    /**
     * Cancel one of the customer's own pending bookings.
     *
     * Previously /PendingBookings?bookingslug={id} deleted ANY booking by id on page load,
     * with no ownership or status check (and was triggerable by a link from another site).
     */
    public function cancel(int $id): void
    {
        $deleted = CartModel::whereKey($id)
            ->forCustomer(app(CustomerAuth::class)->email())
            ->pending()
            ->delete();

        $this->dispatch('alert', ...($deleted
            ? ['type' => 'success', 'message' => 'Booking cancelled.']
            : ['type' => 'error', 'message' => 'That booking can no longer be cancelled.']));
    }

    public function render()
    {
        return view('livewire.bookings-list', [
            'bookings' => $this->customerBookings()->pending()->paginate(10),
            'pending' => true,
            'title' => 'Pending bookings',
            'subtitle' => 'Requests waiting for the provider to confirm.',
        ])->layout('components.layouts.app', ['title' => 'Pending bookings']);
    }
}

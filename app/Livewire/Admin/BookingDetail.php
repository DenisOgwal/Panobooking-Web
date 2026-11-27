<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\AdminComponent;
use App\Models\CartModel;
use App\Models\UserRegistrationModel;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;

/** /admin/bookings/{id}: one booking, with confirm / decline. The note is shown to the customer. */
class BookingDetail extends Component
{
    use AdminComponent;

    #[Locked]
    public int $bookingId;

    public string $note = '';

    public function mount(int $id): void
    {
        $booking = CartModel::findOrFail($id);
        $this->bookingId = $booking->IDs;
        $this->note = (string) $booking->ConfirmReason;
    }

    protected function booking(): CartModel
    {
        return CartModel::findOrFail($this->bookingId);
    }

    public function confirm(): void
    {
        $this->decide(CartModel::CONFIRMED, ['note' => 'nullable|string|max:1000']);
    }

    public function decline(): void
    {
        $this->decide(CartModel::DECLINED, ['note' => 'required|string|max:1000'], ['note.required' => 'Tell the customer why the booking was declined.']);
    }

    public function reopen(): void
    {
        $this->decide(CartModel::PENDING, ['note' => 'nullable|string|max:1000']);
    }

    private function decide(string $status, array $rules, array $messages = []): void
    {
        $this->validate($rules, $messages);
        $booking = $this->booking();
        $booking->forceFill(['Confirmed' => $status, 'ConfirmReason' => trim($this->note) ?: null])->save();
        $this->toast("Booking #{$booking->IDs} is now " . strtolower($status) . '.');
    }

    public function delete(): void
    {
        Gate::authorize('admin.manage');
        $booking = $this->booking();
        $booking->delete();
        $this->flashToast("Booking #{$booking->IDs} deleted.");
        $this->redirectRoute('admin.bookings');
    }

    public function render()
    {
        $booking = $this->booking();

        return view('livewire.admin.booking-detail', [
            'b' => $booking,
            'customer' => $booking->User ? UserRegistrationModel::where('Email', $booking->User)->first() : null,
            'canManage' => Gate::allows('admin.manage'),
        ])->layout('components.layouts.admin', $this->adminLayout('Booking #' . $booking->IDs));
    }
}

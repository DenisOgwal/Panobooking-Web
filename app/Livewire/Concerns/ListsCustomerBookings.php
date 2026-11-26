<?php

namespace App\Livewire\Concerns;

use App\Models\CartModel;
use App\Support\CustomerAuth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/** Searchable, paginated list of the signed-in customer's bookings. */
trait ListsCustomerBookings
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function mountListsCustomerBookings(): void
    {
        if (! app(CustomerAuth::class)->check()) {
            $this->redirect('/Login?return=' . urlencode('/' . request()->path()));
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function customerBookings(): Builder
    {
        return CartModel::forCustomer(app(CustomerAuth::class)->email())
            ->search($this->search)
            ->orderByDesc('IDs');
    }
}

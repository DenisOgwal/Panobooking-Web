<?php

namespace App\Livewire;

use App\Livewire\Concerns\SortsListings;
use App\Models\TourPackages as TourPackageModel;
use Livewire\Component;

/** /TourPackages: upcoming tour packages. */
class TourPackages extends Component
{
    use SortsListings;

    public function sortOptions(): array
    {
        return ['recommended' => 'Soonest first', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'name' => 'Name A–Z'];
    }

    public function render()
    {
        $query = TourPackageModel::upcoming();
        $results = ($this->sort === 'recommended' ? $query->orderBy('TravelDate') : $this->applySort($query, 'PackageFees2', 'PackageName', 'TripDays'))
            ->paginate($this->perPage);

        return view('livewire.tour-packages', ['results' => $results, 'sortOptions' => $this->sortOptions()])
            ->layout('components.layouts.app', ['title' => 'Tour packages']);
    }
}

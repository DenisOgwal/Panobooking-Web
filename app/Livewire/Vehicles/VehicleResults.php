<?php

namespace App\Livewire\Vehicles;

use App\Livewire\Concerns\SortsListings;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

/** Category list or search results for a vehicle vertical. */
abstract class VehicleResults extends Component
{
    use SortsListings;

    abstract protected function type(): string;

    /** Apply the page's filter (category, location, …). */
    abstract protected function filter(Builder $query): Builder;

    /** [title, subtitle, crumb label] */
    abstract protected function heading(): array;

    protected function activeCategory(): ?string
    {
        return null;
    }

    public function sortOptions(): array
    {
        $options = [
            'recommended' => 'Recommended',
            'price_asc' => 'Price: low to high',
            'price_desc' => 'Price: high to low',
            'rating' => 'Top rated',
            'name' => 'Name A–Z',
        ];

        return $this->type() === 'ambulance' ? array_diff_key($options, array_flip(['price_asc', 'price_desc'])) : $options;
    }

    public function render()
    {
        $type = $this->type();
        $c = Vehicles::config($type);
        [$title, $subtitle, $crumb] = $this->heading();

        $results = $this->applySort($this->filter(Vehicles::query($type)), 'Price', 'CarSpec', 'Ratings')
            ->paginate($this->perPage);

        return view('livewire.vehicles.results', [
            'type' => $type,
            'c' => $c,
            'results' => $results,
            'title' => $title,
            'subtitle' => $subtitle,
            'crumbs' => [[$c['plural'], $c['landing']], [$crumb]],
            'categories' => Vehicles::categories($type),
            'activeCategory' => $this->activeCategory(),
            'sortOptions' => $this->sortOptions(),
        ])->layout('components.layouts.app', ['title' => $title]);
    }
}

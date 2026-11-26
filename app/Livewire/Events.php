<?php

namespace App\Livewire;

use App\Models\events as EventModel;
use Livewire\Component;
use Livewire\WithPagination;

/** /Events */
class Events extends Component
{
    use WithPagination;

    public function render()
    {
        $items = EventModel::upcoming()->orderBy('StartDate')->paginate(12);

        return view('livewire.experiences', [
            'items' => $items,
            'kind' => 'event',
            'title' => 'Upcoming events',
            'subtitle' => 'Festivals, races and gatherings across Uganda.',
        ])->layout('components.layouts.app', ['title' => 'Upcoming events']);
    }
}

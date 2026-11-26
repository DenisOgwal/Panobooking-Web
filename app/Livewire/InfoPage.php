<?php

namespace App\Livewire;

use Livewire\Component;

/** Static information page; copy lives in resources/views/livewire/content/{partial}.blade.php. */
abstract class InfoPage extends Component
{
    /** [title, subtitle, content partial, icon] */
    abstract protected function page(): array;

    public function render()
    {
        [$title, $subtitle, $partial, $icon] = $this->page();

        return view('livewire.info-page', compact('title', 'subtitle', 'partial', 'icon'))
            ->layout('components.layouts.app', ['title' => $title]);
    }
}

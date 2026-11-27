<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Support\Facades\Gate;

/**
 * Shared by every admin Livewire component. Checks access on every request, the initial
 * page load and each Livewire update, so route middleware is never the only gate.
 */
trait AdminComponent
{
    public function bootAdminComponent(): void
    {
        abort_unless(Gate::allows('admin.access'), 403);
    }

    protected function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('alert', type: $type, message: $message);
    }

    protected function flashToast(string $message, string $type = 'success'): void
    {
        session()->flash('alert', ['type' => $type, 'message' => $message]);
    }

    protected function adminLayout(string $title): array
    {
        return ['title' => $title];
    }
}

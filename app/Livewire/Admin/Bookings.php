<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\AdminComponent;
use App\Models\CartModel;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/** /admin/bookings: every booking request, filterable by status, type and date. */
class Bookings extends Component
{
    use AdminComponent, WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $type = '';

    #[Url(except: '')]
    public string $from = '';

    #[Url(except: '')]
    public string $to = '';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'type', 'from', 'to'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'status', 'type', 'from', 'to');
        $this->resetPage();
    }

    protected function query(): Builder
    {
        $query = CartModel::query();

        $term = trim($this->search);
        if ($term !== '') {
            $like = '%' . addcslashes($term, '%_\\') . '%';
            $query->where(fn ($q) => $q->search($term)
                ->orWhere('User', 'like', $like)->orWhere('UserNames', 'like', $like)->orWhere('OrderCode', 'like', $like)
                ->when(ctype_digit($term), fn ($q) => $q->orWhere('IDs', (int) $term)));
        }
        if (in_array($this->status, CartModel::STATUSES, true)) {
            $query->where('Confirmed', $this->status);
        }
        if ($this->type !== '') {
            $query->where('BookingType', $this->type === '__none' ? null : $this->type);
        }
        if ($this->validDate($this->from)) {
            $query->where('created_at', '>=', $this->from . ' 00:00:00');
        }
        if ($this->validDate($this->to)) {
            $query->where('created_at', '<=', $this->to . ' 23:59:59');
        }

        return $query->orderByDesc('IDs');
    }

    private function validDate(string $value): bool
    {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
    }

    public function render()
    {
        $counts = CartModel::query()->selectRaw('Confirmed, COUNT(*) as total')->groupBy('Confirmed')->pluck('total', 'Confirmed');

        return view('livewire.admin.bookings', [
            'bookings' => $this->query()->paginate(25),
            'types' => CartModel::query()->whereNotNull('BookingType')->distinct()->orderBy('BookingType')->pluck('BookingType'),
            'counts' => $counts,
        ])->layout('components.layouts.admin', $this->adminLayout('Bookings'));
    }
}

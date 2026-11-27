<?php

namespace App\Livewire\Admin;

use App\Admin\Column;
use App\Admin\RecordActions;
use App\Admin\Resource;
use App\Admin\ResourceRegistry;
use App\Livewire\Admin\Concerns\AdminComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/** /admin/r/{resource}: searchable, filterable, sortable list of any registered table. */
class ResourceIndex extends Component
{
    use AdminComponent, WithPagination;

    #[Locked]
    public string $resource;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: [])]
    public array $filter = [];

    #[Url(except: '')]
    public string $sort = '';

    #[Url(except: '')]
    public string $dir = '';

    /** Selected primary keys for bulk actions. */
    public array $selected = [];

    public int $perPage = 25;

    public function mount(string $resource): void
    {
        ResourceRegistry::resolve($resource);
        $this->resource = $resource;
        $this->filter = array_intersect_key($this->filter, $this->def()::filters());
    }

    /** @return class-string<Resource> */
    protected function def(): string
    {
        return ResourceRegistry::resolve($this->resource);
    }

    public function updatedSearch(): void { $this->resetPage(); $this->selected = []; }
    public function updatedFilter(): void { $this->resetPage(); $this->selected = []; }

    public function clearFilters(): void
    {
        $this->reset('search', 'filter', 'selected');
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if (! in_array($column, $this->sortableColumns(), true)) {
            return;
        }
        $this->dir = $this->sort === $column && $this->dir === 'asc' ? 'desc' : 'asc';
        $this->sort = $column;
    }

    public function approveSelected(bool $on = true): void
    {
        $count = RecordActions::approve($this->def(), $this->selectedIds(), $on);
        $this->selected = [];
        $this->toast($count . ' ' . ($on ? 'approved' : 'unapproved') . '.');
    }

    public function approve(int|string $id, bool $on = true): void
    {
        RecordActions::approve($this->def(), [$id], $on);
        $this->toast($on ? 'Approved.' : 'Approval removed.');
    }

    public function delete(int|string $id): void
    {
        $def = $this->def();
        $record = $def::query()->whereKey($id)->first();
        if (! $record) {
            return;
        }
        $title = $def::title($record);
        if ($error = RecordActions::delete($def, $record)) {
            $this->toast($error, 'error');

            return;
        }
        $this->selected = array_values(array_diff($this->selected, [(string) $id]));
        $this->toast("Deleted “{$title}”.");
    }

    public function deleteSelected(): void
    {
        Gate::authorize('admin.manage');
        $def = $this->def();
        $deleted = 0;
        $errors = [];
        foreach ($def::query()->whereKey($this->selectedIds())->get() as $record) {
            $error = RecordActions::delete($def, $record);
            $error ? $errors[] = $error : $deleted++;
        }
        $this->selected = [];
        $this->toast("Deleted {$deleted}." . ($errors ? ' ' . implode(' ', array_unique($errors)) : ''), $errors ? 'warning' : 'success');
    }

    private function selectedIds(): array
    {
        return array_values(array_filter(array_map('strval', $this->selected), fn ($id) => $id !== ''));
    }

    /** @return list<string> */
    private function sortableColumns(): array
    {
        return collect($this->def()::columns())->filter(fn (Column $c) => $c->sortable)->map(fn (Column $c) => $c->name)->values()->all();
    }

    protected function query(): Builder
    {
        $def = $this->def();
        $query = $def::query();

        $term = trim($this->search);
        if ($term !== '') {
            $like = '%' . addcslashes($term, '%_\\') . '%';
            $columns = $def::searchColumns();
            $query->where(function (Builder $q) use ($columns, $like, $term, $def) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', $like);
                }
                if (ctype_digit($term)) {
                    $q->orWhere($def::newModel()->getKeyName(), (int) $term);
                }
            });
        }

        $approval = $def::approval();
        foreach ($this->filter as $column => $value) {
            if ($value === '' || $value === null || ! array_key_exists($column, $def::filters())) {
                continue;
            }
            if ($value === '__pending' && $approval && $approval['column'] === $column) {
                $query->where(fn ($q) => $q->whereNull($column)->orWhere($column, '!=', $approval['on']));
            } elseif ($value === '__empty') {
                $query->where(fn ($q) => $q->whereNull($column)->orWhere($column, ''));
            } else {
                $query->where($column, $value);
            }
        }

        [$defaultSort, $defaultDir] = $def::defaultSort();
        $sort = in_array($this->sort, $this->sortableColumns(), true) ? $this->sort : $defaultSort;
        $dir = in_array($this->dir, ['asc', 'desc'], true) ? $this->dir : $defaultDir;
        $key = $def::newModel()->getKeyName();

        return $query->orderBy($sort, $dir)->when($sort !== $key, fn ($q) => $q->orderBy($key, 'desc'));
    }

    public function render()
    {
        $def = $this->def();
        $filters = [];
        foreach ($def::filters() as $column => [$label, $options]) {
            $filters[$column] = [$label, $options instanceof \Closure ? $options() : $options];
        }

        return view('livewire.admin.resource-index', [
            'def' => $def,
            'records' => $this->query()->paginate($this->perPage),
            'columns' => $def::columns(),
            'filters' => $filters,
            'canManage' => Gate::allows('admin.manage'),
            'activeSort' => $this->sort ?: $def::defaultSort()[0],
            'activeDir' => $this->dir ?: $def::defaultSort()[1],
        ])->layout('components.layouts.admin', $this->adminLayout($def::label()));
    }
}

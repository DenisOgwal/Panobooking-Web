@php
    use App\Admin\Column;
    $approval = $def::approval();
    $media = $def::media();
    $hasBulk = $approval || $canManage;
    $activeFilters = array_filter($filter, fn ($v) => $v !== '' && $v !== null);
@endphp
<div>
    <div class="admin-page-head">
        <div>
            <h1 class="h3 mb-0">{{ $def::label() }}</h1>
            <div class="text-muted small">{{ number_format($records->total()) }} {{ $records->total() === 1 ? $def::singular() : strtolower($def::label()) }}</div>
        </div>
        @if ($def::canCreate())
            <a class="btn btn-primary" href="{{ route('admin.resources.create', ['resource' => $resource]) }}">
                <i class="bi bi-plus-lg me-1"></i>Add {{ $def::singular() }}
            </a>
        @endif
    </div>

    <div class="admin-card">
        <div class="admin-toolbar">
            <div class="input-icon flex-grow-1">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label class="visually-hidden" for="admin-search">Search</label>
                <input id="admin-search" class="form-control" type="search" placeholder="Search {{ strtolower($def::label()) }}…" wire:model.live.debounce.300ms="search">
            </div>
            @foreach ($filters as $column => [$label, $opts])
                <div>
                    <label class="visually-hidden" for="filter-{{ $column }}">{{ $label }}</label>
                    <select id="filter-{{ $column }}" class="form-select" wire:model.live="filter.{{ $column }}">
                        <option value="">{{ $label }}: all</option>
                        @foreach ($opts as $value => $text)
                            <option value="{{ $value }}">{{ $text }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
            @if ($search !== '' || $activeFilters)
                <button type="button" class="btn btn-link" wire:click="clearFilters">Clear</button>
            @endif
        </div>

        @if ($hasBulk && count($selected))
            <div class="admin-bulkbar">
                <strong>{{ count($selected) }} selected</strong>
                @if ($approval)
                    <button type="button" class="btn btn-sm btn-success" wire:click="approveSelected(true)"><i class="bi bi-check2 me-1"></i>Approve</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="approveSelected(false)">Unapprove</button>
                @endif
                @if ($canManage)
                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="deleteSelected"
                            wire:confirm="Delete {{ count($selected) }} {{ strtolower($def::label()) }}? This cannot be undone."><i class="bi bi-trash me-1"></i>Delete</button>
                @endif
                <button type="button" class="btn btn-sm btn-link" wire:click="$set('selected', [])">Clear selection</button>
            </div>
        @endif

        <div class="table-responsive" wire:loading.class="opacity-50">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        @if ($hasBulk)<th class="admin-check"><span class="visually-hidden">Select</span></th>@endif
                        @foreach ($columns as $col)
                            <th scope="col" @class(['admin-thumb-col' => $col->format === 'photo'])>
                                @if ($col->sortable)
                                    <button type="button" class="admin-sort" wire:click="sortBy('{{ $col->name }}')">
                                        {{ $col->label }}
                                        @if ($activeSort === $col->name)<i class="bi bi-caret-{{ $activeDir === 'asc' ? 'up' : 'down' }}-fill" aria-hidden="true"></i>@endif
                                    </button>
                                @else
                                    {{ $col->label }}
                                @endif
                            </th>
                        @endforeach
                        <th scope="col" class="text-end"><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        @php $key = (string) $record->getKey(); $editUrl = route('admin.resources.edit', ['resource' => $resource, 'id' => $key]); @endphp
                        <tr wire:key="row-{{ $key }}">
                            @if ($hasBulk)
                                <td class="admin-check"><input class="form-check-input" type="checkbox" value="{{ $key }}" wire:model.live="selected" aria-label="Select {{ $def::title($record) }}"></td>
                            @endif
                            @foreach ($columns as $i => $col)
                                @php $value = $col->display($record, $media); @endphp
                                <td>
                                    @if ($col->format === 'photo')
                                        <img class="admin-thumb" src="{{ $value }}" alt="" loading="lazy" data-fallback="{{ \App\Support\Media::placeholder() }}">
                                    @elseif ($col->format === 'badge')
                                        @if ($value !== '')<span class="badge text-bg-{{ Column::badgeTone($value) }}">{{ $value }}</span>@else<span class="text-muted">—</span>@endif
                                    @elseif ($loop->index === ($media ? 1 : 0))
                                        <a class="fw-semibold" href="{{ $editUrl }}">{{ $value !== '' ? $value : '#' . $key }}</a>
                                    @else
                                        {{ $value !== '' ? $value : '—' }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-end text-nowrap">
                                @if ($approval)
                                    @if ($record->getAttribute($approval['column']) === $approval['on'])
                                        <button type="button" class="btn btn-sm btn-light" wire:click="approve('{{ $key }}', false)" title="Unapprove"><i class="bi bi-x-circle"></i><span class="visually-hidden">Unapprove</span></button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-success" wire:click="approve('{{ $key }}', true)" title="Approve"><i class="bi bi-check2"></i><span class="visually-hidden">Approve</span></button>
                                    @endif
                                @endif
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i><span class="visually-hidden">More actions</span></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ $editUrl }}"><i class="bi bi-pencil me-2"></i>{{ $def::canEdit() ? 'Edit' : 'View' }}</a></li>
                                        @foreach ($def::links($record) as [$label, $url, $icon])
                                            <li><a class="dropdown-item" href="{{ $url }}" @if (str_contains($icon, 'up-right')) target="_blank" rel="noopener" @endif><i class="bi {{ $icon }} me-2"></i>{{ $label }}</a></li>
                                        @endforeach
                                        @if ($canManage)
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button type="button" class="dropdown-item text-danger" wire:click="delete('{{ $key }}')"
                                                        wire:confirm="Delete “{{ $def::title($record) }}”? This cannot be undone."><i class="bi bi-trash me-2"></i>Delete</button></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + ($hasBulk ? 2 : 1) }}">
                                <x-empty-state :title="$search !== '' || $activeFilters ? 'No matches' : 'No ' . strtolower($def::label()) . ' yet'" icon="bi-inbox">
                                    @if ($search !== '' || $activeFilters)Try another search or clear the filters.@endif
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($records->hasPages())
            <div class="admin-card-foot">{{ $records->links() }}</div>
        @endif
    </div>
</div>

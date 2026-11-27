@php use App\Admin\PropertyDetailSections; use App\Support\Features; @endphp
<div>
    <div class="admin-page-head">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.resources.index', ['resource' => $resourceSlug]) }}">{{ $resourceLabel }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.resources.edit', ['resource' => $resourceSlug, 'id' => $property->IDs]) }}">{{ $name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Amenities & surroundings</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">{{ $name }}</h1>
            <div class="text-muted small">Amenities, policies and surroundings shown on the property page.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.resources.edit', ['resource' => $resourceSlug, 'id' => $property->IDs]) }}"><i class="bi bi-pencil me-1"></i>Edit details</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ $siteUrl }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right me-1"></i>View on site</a>
        </div>
    </div>

    <ul class="nav nav-tabs admin-tabs mb-4" role="tablist">
        @foreach (PropertyDetailSections::TABS as $key => $label)
            <li class="nav-item" role="presentation">
                <button type="button" role="tab" @class(['nav-link', 'active' => $tab === $key]) aria-selected="{{ $tab === $key ? 'true' : 'false' }}"
                        wire:click="$set('tab', '{{ $key }}')">{{ $label }}</button>
            </li>
        @endforeach
    </ul>

    <div class="admin-detail-grid">
        @foreach ($sections as $key => $s)
            <section class="admin-card admin-card-body" wire:key="sec-{{ $key }}">
                <form wire:submit="saveSection('{{ $key }}')" novalidate>
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h6 admin-section-title mb-0">{{ $s['label'] }}</h2>
                        <button type="submit" class="btn btn-primary btn-sm ms-auto" wire:loading.attr="disabled" wire:target="saveSection('{{ $key }}')">Save</button>
                    </div>

                    @if ($s['kind'] === 'flags')
                        <div class="admin-checkgrid">
                            @foreach ($flags[$key] ?? [] as $column => $on)
                                <div class="form-check" wire:key="fl-{{ $key }}-{{ $column }}">
                                    <input class="form-check-input" type="checkbox" id="fl-{{ $key }}-{{ $column }}" wire:model="flags.{{ $key }}.{{ $column }}">
                                    <label class="form-check-label" for="fl-{{ $key }}-{{ $column }}">{{ Features::label($column) }}</label>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($s['kind'] === 'form')
                        <div class="row g-3">
                            @foreach ($s['fields'] as $column => $label)
                                <div class="col-md-6">
                                    <label class="form-label" for="fm-{{ $key }}-{{ $column }}">{{ $label }}</label>
                                    <input id="fm-{{ $key }}-{{ $column }}" class="form-control" type="text" wire:model="forms.{{ $key }}.{{ $column }}">
                                    @error("forms.$key.$column")<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                            @endforeach
                        </div>
                    @else
                        @php $multiline = $key === 'propertydescription'; $cols = count($s['fields']); @endphp
                        @if (isset($suggestions[$key]))
                            <datalist id="dl-{{ $key }}">@foreach ($suggestions[$key] as $v)<option value="{{ $v }}"></option>@endforeach</datalist>
                        @endif
                        <div class="admin-rows">
                            @foreach ($rows[$key] ?? [] as $id => $values)
                                <div class="admin-row" wire:key="row-{{ $key }}-{{ $id }}">
                                    @foreach ($s['fields'] as $column => $label)
                                        <div class="flex-grow-1">
                                            <label class="visually-hidden" for="r-{{ $key }}-{{ $id }}-{{ $column }}">{{ $label }}</label>
                                            @if ($multiline)
                                                <textarea id="r-{{ $key }}-{{ $id }}-{{ $column }}" class="form-control" rows="3" wire:model="rows.{{ $key }}.{{ $id }}.{{ $column }}"></textarea>
                                            @else
                                                <input id="r-{{ $key }}-{{ $id }}-{{ $column }}" class="form-control form-control-sm" type="text" placeholder="{{ $label }}"
                                                       @if (isset($suggestions[$key])) list="dl-{{ $key }}" @endif wire:model="rows.{{ $key }}.{{ $id }}.{{ $column }}">
                                            @endif
                                            @error("rows.$key.$id.$column")<div class="form-error">{{ $message }}</div>@enderror
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-light" wire:click="deleteRow('{{ $key }}', {{ $id }})" title="Remove">
                                        <i class="bi bi-x-lg"></i><span class="visually-hidden">Remove</span>
                                    </button>
                                </div>
                            @endforeach

                            <div class="admin-row admin-row-new">
                                @foreach ($s['fields'] as $column => $label)
                                    <div class="flex-grow-1">
                                        <label class="visually-hidden" for="n-{{ $key }}-{{ $column }}">New {{ strtolower($label) }}</label>
                                        @if ($multiline)
                                            <textarea id="n-{{ $key }}-{{ $column }}" class="form-control" rows="3" placeholder="Add a paragraph…" wire:model="newRow.{{ $key }}.{{ $column }}"></textarea>
                                        @else
                                            <input id="n-{{ $key }}-{{ $column }}" class="form-control form-control-sm" type="text" placeholder="{{ $cols === 1 ? 'Add ' . strtolower($label) . '…' : $label }}"
                                                   @if (isset($suggestions[$key])) list="dl-{{ $key }}" @endif wire:model="newRow.{{ $key }}.{{ $column }}">
                                        @endif
                                        @error("newRow.$key.$column")<div class="form-error">{{ $message }}</div>@enderror
                                    </div>
                                @endforeach
                                <span class="admin-row-spacer" aria-hidden="true"></span>
                            </div>
                            <div class="form-text">Fill in the last line and press Save to add it. Clearing a line removes it.</div>
                        </div>
                    @endif
                </form>
            </section>
        @endforeach
    </div>
</div>

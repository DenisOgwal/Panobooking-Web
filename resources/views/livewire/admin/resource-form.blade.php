@php use App\Support\Features; @endphp
<div>
    <div class="admin-page-head">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.resources.index', ['resource' => $resource]) }}">{{ $def::label() }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $record ? ($readOnly ? 'View' : 'Edit') : 'New' }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">{{ $heading }}</h1>
            @if ($record && $record->updated_at)
                <div class="text-muted small">Last updated {{ $record->updated_at->diffForHumans() }}</div>
            @endif
        </div>
        <div class="d-flex flex-wrap gap-2">
            @foreach ($links as [$label, $url, $icon])
                <a class="btn btn-outline-secondary btn-sm" href="{{ $url }}" @if (str_contains($icon, 'up-right')) target="_blank" rel="noopener" @endif>
                    <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <form wire:submit="save" novalidate>
        <div class="row g-4">
            <div class="col-xl-8">
                <section class="admin-card admin-card-body">
                    <h2 class="h6 admin-section-title">Details</h2>
                    <fieldset @disabled($readOnly)>
                        <div class="row g-3">
                            @foreach ($fields as $field)
                                @php
                                    $name = 'data.' . $field->name;
                                    $id = 'f-' . $field->name;
                                    $span = $field->type === 'textarea' ? 12 : $field->span;
                                @endphp
                                <div class="col-md-{{ max(6, $span) }} col-lg-{{ $span }}" wire:key="field-{{ $field->name }}">
                                    <label class="form-label" for="{{ $id }}">
                                        {{ $field->label }}@if ($field->required && ($field->type !== 'password' || ! $record))<span class="text-danger" aria-hidden="true"> *</span>@endif
                                    </label>
                                    @switch($field->type)
                                        @case('textarea')
                                            <textarea id="{{ $id }}" rows="{{ $field->name === 'Description' ? 6 : 3 }}" @class(['form-control', 'is-invalid' => $errors->has($name)]) wire:model="{{ $name }}"></textarea>
                                            @break
                                        @case('select')
                                        @case('yesno')
                                            <select id="{{ $id }}" @class(['form-select', 'is-invalid' => $errors->has($name)]) wire:model="{{ $name }}">
                                                @unless ($field->required && $field->type === 'yesno')<option value="">—</option>@endunless
                                                @foreach ($options[$field->name] ?? [] as $value => $text)
                                                    <option value="{{ $value }}">{{ $text }}</option>
                                                @endforeach
                                            </select>
                                            @break
                                        @case('password')
                                            <input id="{{ $id }}" type="password" autocomplete="new-password" @class(['form-control', 'is-invalid' => $errors->has($name)]) wire:model="{{ $name }}">
                                            @break
                                        @default
                                            <input id="{{ $id }}"
                                                   type="{{ match ($field->type) { 'number', 'money' => 'number', 'date' => 'date', 'email' => 'email', default => 'text' } }}"
                                                   @if (in_array($field->type, ['number', 'money'])) step="any" @endif
                                                   @if (isset($suggestions[$field->name])) list="dl-{{ $field->name }}" @endif
                                                   @class(['form-control', 'is-invalid' => $errors->has($name)]) wire:model="{{ $name }}">
                                            @if (isset($suggestions[$field->name]))
                                                <datalist id="dl-{{ $field->name }}">
                                                    @foreach ($suggestions[$field->name] as $s)<option value="{{ $s }}"></option>@endforeach
                                                </datalist>
                                            @endif
                                    @endswitch
                                    @error($name)<div class="form-error">{{ $message }}</div>@enderror
                                    @if ($field->help && ! $errors->has($name))<div class="form-text">{{ $field->help }}</div>@endif
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                </section>

                @if ($checklist)
                    <section class="admin-card admin-card-body mt-4">
                        <h2 class="h6 admin-section-title">{{ $checklist['label'] }}</h2>
                        <fieldset @disabled($readOnly)>
                            <div class="admin-checkgrid">
                                @foreach ($checklistColumns as $column)
                                    <div class="form-check" wire:key="chk-{{ $column }}">
                                        <input class="form-check-input" type="checkbox" id="chk-{{ $column }}" wire:model="checks.{{ $column }}">
                                        <label class="form-check-label" for="chk-{{ $column }}">{{ Features::label($column) }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @foreach ($checklist['text'] ?? [] as $column => $label)
                                <div class="mt-3">
                                    <label class="form-label" for="txt-{{ $column }}">{{ $label }}</label>
                                    <input id="txt-{{ $column }}" class="form-control" type="text" wire:model="checkText.{{ $column }}">
                                    @error('checkText.' . $column)<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                            @endforeach
                        </fieldset>
                    </section>
                @endif
            </div>

            <div class="col-xl-4">
                @if ($mediaType)
                    <section class="admin-card admin-card-body">
                        <h2 class="h6 admin-section-title">{{ $singlePhoto ? 'Photo' : 'Photos' }}</h2>
                        @if ($photos)
                            <div class="admin-photos">
                                @foreach ($photos as $i => $url)
                                    <figure class="admin-photo" wire:key="photo-{{ $i }}-{{ md5($url) }}">
                                        <a href="{{ $url }}" target="_blank" rel="noopener"><img src="{{ $url }}?v={{ $record?->updated_at?->timestamp }}" alt="Photo {{ $i + 1 }}" loading="lazy" data-fallback="{{ \App\Support\Media::placeholder() }}"></a>
                                        <figcaption>
                                            @if ($i === 0)<span class="badge text-bg-primary">Main</span>@elseif (! $readOnly)
                                                <button type="button" class="btn btn-link btn-sm p-0" wire:click="makeMainPhoto({{ $i + 1 }})">Make main</button>
                                            @endif
                                            @if (! $readOnly)
                                                <button type="button" class="btn btn-link btn-sm p-0 text-danger ms-auto" wire:click="removePhoto({{ $i + 1 }})"
                                                        wire:confirm="Remove this photo?">Remove</button>
                                            @endif
                                        </figcaption>
                                    </figure>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small">No photos yet.</p>
                        @endif

                        @unless ($readOnly)
                            <label class="form-label mt-3" for="uploads">{{ $singlePhoto ? 'Replace photo' : 'Add photos' }}</label>
                            <input id="uploads" type="file" class="form-control" accept="image/jpeg,image/png,image/webp" wire:model="uploads" @unless ($singlePhoto) multiple @endunless>
                            <div wire:loading wire:target="uploads" class="small text-muted mt-1">Uploading…</div>
                            @error('uploads')<div class="form-error">{{ $message }}</div>@enderror
                            @error('uploads.*')<div class="form-error">{{ $message }}</div>@enderror
                            @if ($uploads)
                                <ul class="list-unstyled small mt-2 mb-0">
                                    @foreach ($uploads as $i => $file)
                                        <li class="d-flex align-items-center gap-2" wire:key="up-{{ $i }}">
                                            <i class="bi bi-image"></i>{{ \Illuminate\Support\Str::limit($file->getClientOriginalName(), 34) }}
                                            <button type="button" class="btn btn-link btn-sm p-0 ms-auto" wire:click="removeUpload({{ $i }})">Remove</button>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="form-text">Photos are stored when you save.</div>
                            @else
                                <div class="form-text">JPEG, PNG or WebP, up to 5 MB each. Photos are served from {{ parse_url(config('panobooking.media_url'), PHP_URL_HOST) }}.</div>
                            @endif
                        @endunless
                    </section>
                @endif

                <section @class(['admin-card admin-card-body', 'mt-4' => $mediaType])>
                    @unless ($readOnly)
                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save"><i class="bi bi-check2 me-1"></i>{{ $record ? 'Save changes' : 'Create ' . $def::singular() }}</span>
                            <span wire:loading wire:target="save">Saving…</span>
                        </button>
                    @endunless
                    <a class="btn btn-light w-100 mt-2" href="{{ route('admin.resources.index', ['resource' => $resource]) }}">Back to {{ strtolower($def::label()) }}</a>
                    @if ($record && $canManage)
                        <button type="button" class="btn btn-outline-danger w-100 mt-3" wire:click="delete"
                                wire:confirm="Delete “{{ $heading }}”? This cannot be undone."><i class="bi bi-trash me-1"></i>Delete</button>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger small mt-3 mb-0" role="alert">Please fix the highlighted fields.</div>
                    @endif
                </section>
            </div>
        </div>
    </form>
</div>

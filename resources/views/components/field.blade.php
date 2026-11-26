{{--
    Labelled form field with validation message.
    <x-field label="Email" name="email" type="email" wire:model.blur="email" icon="bi-envelope" />
    <x-field label="Notes" name="notes" textarea rows="3" wire:model="notes" />
    <x-field label="Rooms" name="rooms" select wire:model.live="rooms"> <option>…</option> </x-field>
--}}
@props(['label', 'name', 'type' => 'text', 'icon' => null, 'hint' => null, 'textarea' => false, 'select' => false, 'id' => null])
@php $id = $id ?? 'f-' . $name; @endphp
<div class="mb-3">
    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    <div @class(['input-icon' => $icon])>
        @if ($icon)<i class="bi {{ $icon }}" aria-hidden="true"></i>@endif
        @if ($textarea)
            <textarea id="{{ $id }}" name="{{ $name }}" {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}></textarea>
        @elseif ($select)
            <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->class(['form-select', 'is-invalid' => $errors->has($name)]) }}>{{ $slot }}</select>
        @else
            <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>
        @endif
    </div>
    @error($name)<div class="form-error">{{ $message }}</div>@enderror
    @if ($hint && ! $errors->has($name))<div class="form-text">{{ $hint }}</div>@endif
</div>

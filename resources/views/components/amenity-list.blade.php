{{--
    Yes/No feature list. $features: ['Label' => 'Yes'|'No'|bool, …]. "No" items are hidden
    unless :show-missing is set.
--}}
@props(['features' => [], 'showMissing' => false])
@php
    $isOn = fn ($v) => $v === true || (is_string($v) && strtolower(trim($v)) === 'yes');
    $items = collect($features)->filter(fn ($v) => $showMissing || $isOn($v));
@endphp
@if ($items->isNotEmpty())
    <ul {{ $attributes->class('amenity-list') }}>
        @foreach ($items as $label => $value)
            <li @class(['is-off' => ! $isOn($value)])>
                <i class="bi {{ $isOn($value) ? 'bi-check-circle-fill' : 'bi-x-circle' }}" aria-hidden="true"></i>
                <span>{{ $label }}</span>
                @unless ($isOn($value))<span class="visually-hidden">(not available)</span>@endunless
            </li>
        @endforeach
    </ul>
@endif

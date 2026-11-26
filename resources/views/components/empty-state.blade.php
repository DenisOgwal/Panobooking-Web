@props(['title' => 'Nothing to show yet', 'icon' => 'bi-search'])
<div {{ $attributes->class('empty-state') }}>
    <i class="bi {{ $icon }}" aria-hidden="true"></i>
    <h3>{{ $title }}</h3>
    @if (trim($slot) !== '')<p class="mb-0">{{ $slot }}</p>@endif
</div>

@props(['title', 'eyebrow' => null, 'subtitle' => null, 'center' => false])
<div {{ $attributes->class(['section-heading', 'text-center' => $center, 'd-flex flex-wrap align-items-end justify-content-between gap-3' => ! $center && isset($actions)]) }}>
    <div>
        @if ($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
        <h2>{{ $title }}</h2>
        @if ($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)
        <div>{{ $actions }}</div>
    @endisset
</div>

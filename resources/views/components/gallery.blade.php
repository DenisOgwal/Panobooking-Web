{{-- Photo grid with lightbox. $images: list of URLs. --}}
@props(['images' => [], 'alt' => '', 'name' => 'gallery'])
@php $images = array_values($images); $extra = max(0, count($images) - 5); @endphp
<div {{ $attributes->class('gallery-grid') }} data-count="{{ min(count($images), 5) }}">
    @foreach ($images as $i => $src)
        <a href="{{ $src }}" data-gallery="{{ $name }}" aria-label="Photo {{ $i + 1 }} of {{ count($images) }}">
            <img src="{{ $src }}" data-fallback="{{ \App\Support\Media::placeholder() }}" alt="{{ $alt }} photo {{ $i + 1 }}" @if ($i > 0) loading="lazy" @endif>
            @if ($i === 4 && $extra > 0)
                <span class="more">+{{ $extra }} photos</span>
            @endif
        </a>
    @endforeach
</div>

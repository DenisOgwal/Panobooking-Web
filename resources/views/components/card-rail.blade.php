{{--
    Horizontally scrolling row of cards (Swiper). Each child must be a <div class="swiper-slide">.
    <x-card-rail title="Trending stays" subtitle="…" :per-view="4"> @foreach(...) <div class="swiper-slide">…</div> @endforeach </x-card-rail>
--}}
@props(['title' => null, 'subtitle' => null, 'eyebrow' => null, 'perView' => 4])
<div {{ $attributes->class('card-rail') }} data-rail data-per-view="{{ $perView }}">
    @if ($title)
        <x-section-heading :title="$title" :subtitle="$subtitle" :eyebrow="$eyebrow">
            <x-slot:actions>
                <div class="rail-nav">
                    <button type="button" data-rail-prev aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
                    <button type="button" data-rail-next aria-label="Next"><i class="bi bi-chevron-right"></i></button>
                </div>
            </x-slot:actions>
        </x-section-heading>
    @endif
    <div class="swiper">
        <div class="swiper-wrapper">
            {{ $slot }}
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

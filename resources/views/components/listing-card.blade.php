{{--
    Card for any listing (hotel, guest house, car, taxi, ambulance, tour).
    Usage:
      <x-listing-card :href="..." :image="..." title="..." location="..." :rating="4.5" :price="180000" unit="per night" badge="Featured" />
    Extra meta (e.g. car specs) can go in the default slot.
--}}
@props([
    'href',
    'image',
    'title',
    'location' => null,
    'rating' => null,
    'price' => null,
    'pricePrefix' => 'From',
    'unit' => null,
    'badge' => null,
    'badgeAccent' => false,
    'cta' => 'View',
])
<article {{ $attributes->class('listing-card') }}>
    <div class="listing-media">
        <img src="{{ $image }}" data-fallback="{{ \App\Support\Media::placeholder() }}" alt="{{ $title }}" loading="lazy" decoding="async">
        @if ($badge)
            <span @class(['listing-badge', 'is-accent' => $badgeAccent])>{{ $badge }}</span>
        @endif
    </div>
    <div class="listing-body">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <h3 class="listing-title"><a href="{{ $href }}">{{ $title }}</a></h3>
            <x-rating :value="$rating" chip />
        </div>
        @if ($location)
            <div class="listing-meta"><i class="bi bi-geo-alt" aria-hidden="true"></i>{{ $location }}</div>
        @endif
        @if (trim($slot) !== '')
            <div class="listing-meta">{{ $slot }}</div>
        @endif
        <div class="listing-footer">
            @if (is_numeric($price) && $price > 0)
                <x-price :amount="$price" :prefix="$pricePrefix" :unit="$unit" />
            @else
                <span></span>
            @endif
            <a href="{{ $href }}" class="btn btn-soft btn-sm listing-cta" tabindex="-1" aria-hidden="true">{{ $cta }}</a>
        </div>
    </div>
</article>

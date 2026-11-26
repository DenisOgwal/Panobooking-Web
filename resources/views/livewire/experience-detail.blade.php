{{-- Tourism site or event detail. Expects $item, $kind ('site'|'event'), $gallery, $more --}}
@php
    use App\Support\Media;
    use Illuminate\Support\Carbon;
    $isEvent = $kind === 'event';
    $name = $isEvent ? $item->EventName : $item->SiteName;
    $nearbyUrl = route($isEvent ? 'EventsProperties' : 'TourismProperties', ['searchslug' => $item->SearchKeyWords, 'siteslug' => $name, 'cityslug' => $item->City]);
@endphp
<div>
    <section class="section-sm">
        <div class="container-xl">
            <nav aria-label="breadcrumb"><ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ $isEvent ? '/Events' : '/TourismSites' }}">{{ $isEvent ? 'Events' : 'Tourism sites' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
            </ol></nav>
            <header class="listing-header mb-3">
                <span class="eyebrow">{{ $isEvent ? 'Event' : 'Tourism site' }}</span>
                <h1 class="mb-1">{{ $name }}</h1>
                <div class="meta">
                    <span><i class="bi bi-geo-alt"></i> {{ collect([$item->City, $item->Country])->filter()->implode(', ') }}</span>
                    @if ($isEvent && $item->StartDate)
                        <span><i class="bi bi-calendar-event"></i>
                            {{ Carbon::parse($item->StartDate)->format('j M Y') }}@if ($item->EndDate && $item->EndDate !== $item->StartDate) – {{ Carbon::parse($item->EndDate)->format('j M Y') }}@endif
                        </span>
                    @endif
                </div>
            </header>
            <x-gallery :images="$gallery" :alt="$name" :name="$kind . '-' . $item->ID" />
        </div>
    </section>

    <section class="section-sm pt-0">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="detail-section">
                        <h2>About</h2>
                        @if (filled($item->ShortDescription))<p class="lead fs-6 fw-semibold">{{ $item->ShortDescription }}</p>@endif
                        <p class="mb-0">{!! nl2br(e($item->Description)) !!}</p>
                    </div>
                </div>
                <aside class="col-lg-4">
                    <div class="booking-box">
                        <h2 class="h5">Plan your visit</h2>
                        <p class="small text-muted">Find a place to stay, a car or an airport transfer near {{ $name }}.</p>
                        <a class="btn btn-primary w-100" href="{{ $nearbyUrl }}"><i class="bi bi-building me-1"></i>Stays &amp; transport nearby</a>
                        <a class="btn btn-soft w-100 mt-2" href="{{ route('DestinationsList', ['destinationslug' => $item->City]) }}">All stays in {{ $item->City }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($more->isNotEmpty())
        <section class="section bg-white border-top">
            <div class="container-xl">
                <x-section-heading :title="$isEvent ? 'More upcoming events' : 'More places to visit'" />
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($more as $m)
                        <div class="col">
                            <x-listing-card :href="$isEvent ? route('EventsDetails', ['searchslug' => $m->ID]) : route('TourismSiteDetails', ['searchslug' => $m->ID])"
                                :image="Media::image($kind, $m)" :title="$isEvent ? $m->EventName : $m->SiteName" :location="$m->City" :cta="$isEvent ? 'Details' : 'Explore'" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>

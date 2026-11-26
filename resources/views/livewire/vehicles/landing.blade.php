@php use App\Support\Vehicles; @endphp
<div>
    <section class="hero">
        <div class="container-xl">
            <div class="row align-items-end g-4 mb-4 mb-lg-5">
                <div class="col-lg-8">
                    @if ($offer)
                        <a class="hero-offer mb-4" href="{{ Vehicles::detailUrl($type, $offer) }}">
                            <span class="badge rounded-pill">Offer</span>
                            {{ $offer->CarSpec ?: $offer->TaxiNo }}
                            @if ($type !== 'ambulance' && $offer->Price) from {{ app(\App\Support\Currency::class)->format($offer->Price) }} @endif
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    @endif
                    <h1 class="mb-3">{{ $title }}</h1>
                    <p class="lead mb-0">{{ $lead }}</p>
                </div>
            </div>
            <x-search-panel :active="$c['searchTab']" />
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="section-sm">
            <div class="container-xl">
                <x-section-heading title="Browse by type" />
                <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-xl-5">
                    @foreach ($categories as $name => $count)
                        <div class="col">
                            <a class="category-chip h-100" href="{{ Vehicles::listUrl($type, $name) }}">
                                <span class="icon"><i class="bi {{ $c['icon'] }}"></i></span>
                                <span>{{ $name }}<small>{{ $count }} available</small></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container-xl">
            <x-section-heading :title="$type === 'ambulance' ? 'Ambulances near you' : 'Ready to book'" :subtitle="'Top-rated ' . \Illuminate\Support\Str::lower($c['plural']) . ' available now.'">
                <x-slot:actions><a class="btn btn-soft" href="{{ Vehicles::listUrl($type) }}">View all</a></x-slot:actions>
            </x-section-heading>
            @if ($vehicles->isEmpty())
                <x-empty-state :icon="$c['icon']" :title="'No ' . \Illuminate\Support\Str::lower($c['plural']) . ' listed yet'" />
            @else
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($vehicles as $vehicle)
                        <div class="col">@include('livewire.partials.vehicle-card', ['vehicle' => $vehicle, 'type' => $type])</div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section-sm bg-white border-top">
        <div class="container-xl">
            <div class="row g-3 row-cols-1 row-cols-md-3">
                @foreach (match ($type) {
                    'car' => [['bi-shield-check', 'Insured vehicles', 'Every car is registered and insured by its owner.'],
                              ['bi-person-badge', 'Driver on request', 'Add an experienced local driver for upcountry trips.'],
                              ['bi-cash-coin', 'Pay on pick-up', 'Nothing to pay online. Settle with the provider.']],
                    'taxi' => [['bi-airplane', 'Meet and greet', 'Your driver waits at arrivals with your name.'],
                               ['bi-tag', 'Fixed prices', 'The fare is agreed before you travel.'],
                               ['bi-clock', 'Flight delays covered', 'Drivers track arrivals, so a late flight is no problem.']],
                    default => [['bi-heart-pulse', 'Trained crews', 'Paramedics and equipment listed on every ambulance.'],
                                ['bi-geo-alt', 'Nearest first', 'Search by area to find the closest crew.'],
                                ['bi-telephone', 'Emergency?', 'Call your local emergency number first, then request here.']],
                } as [$icon, $heading, $text])
                    <div class="col">
                        <div class="d-flex gap-3">
                            <span class="category-chip p-0 border-0 bg-transparent"><span class="icon"><i class="bi {{ $icon }}"></i></span></span>
                            <div><strong class="d-block">{{ $heading }}</strong><span class="text-muted small">{{ $text }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

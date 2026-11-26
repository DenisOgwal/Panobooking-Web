{{--
    Shared results page for stays (hotels and guest houses).
    Expects: $results (paginator), $kind ('hotel'|'guesthouse'), $title, $subtitle, $crumbs,
             $facets (collection City => count), $activeFacet, $facetUrl (fn(?string $city): string),
             $sortOptions, $searchTab ('stays')
--}}
@php
    use App\Support\Media;
    $isHotel = $kind === 'hotel';
@endphp
<div>
    <x-page-hero :title="$title" :subtitle="$subtitle" :crumbs="$crumbs" />

    <div class="container-xl">
        @if ($isHotel)
            <x-search-panel :active="$searchTab ?? 'stays'" compact class="mb-4" />
        @else
            <div class="search-panel search-panel-compact mb-4">
                <form class="row g-3 align-items-end p-3" action="{{ route('HomeGuestSearch') }}" method="GET">
                    <div class="col-md-9">
                        <label class="form-label" for="gh-destination">Where would you like to stay?</label>
                        <div class="input-icon"><i class="bi bi-geo-alt"></i>
                            <input id="gh-destination" class="form-control" name="Destination" value="{{ request('Destination') }}" placeholder="Town or area, e.g. Jinja" required>
                        </div>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button class="btn btn-primary btn-icon justify-content-center" type="submit"><i class="bi bi-search"></i>Find home stays</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <section class="section-sm pt-2">
        <div class="container-xl">
            <div class="row g-4">
                @if ($facets->isNotEmpty())
                    <aside class="col-lg-3">
                        <h2 class="h6 text-uppercase text-muted mb-3">Destinations</h2>
                        <div class="d-flex flex-row flex-lg-column gap-2 overflow-auto pb-2">
                            <a @class(['category-chip py-2 flex-shrink-0', 'active' => ! $activeFacet]) href="{{ $facetUrl(null) }}">
                                <span>All<small>{{ $facets->sum() }} {{ \Illuminate\Support\Str::plural('stay', $facets->sum()) }}</small></span>
                            </a>
                            @foreach ($facets as $city => $count)
                                <a @class(['category-chip py-2 flex-shrink-0', 'active' => strcasecmp((string) $activeFacet, (string) $city) === 0]) href="{{ $facetUrl($city) }}">
                                    <span>{{ $city }}<small>{{ $count }} {{ \Illuminate\Support\Str::plural('stay', $count) }}</small></span>
                                </a>
                            @endforeach
                        </div>
                    </aside>
                @endif

                <div @class(['col-lg-9' => $facets->isNotEmpty(), 'col-12' => $facets->isEmpty()])>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <p class="m-0 text-muted" aria-live="polite">
                            <strong class="text-body">{{ $results->total() }}</strong> {{ \Illuminate\Support\Str::plural('result', $results->total()) }}
                        </p>
                        <div class="d-flex align-items-center gap-2">
                            <label for="sort" class="small text-muted text-nowrap">Sort by</label>
                            <select id="sort" class="form-select form-select-sm" wire:model.live="sort">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div wire:loading.class="opacity-50" wire:target="sort,gotoPage,nextPage,previousPage">
                        @if ($results->isEmpty())
                            <x-empty-state icon="bi-building" title="No stays found">
                                Try another destination, or <a href="{{ $facetUrl(null) }}">browse all stays</a>.
                            </x-empty-state>
                        @else
                            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-xl-3">
                                @foreach ($results as $stay)
                                    <div class="col" wire:key="stay-{{ $stay->IDs }}">
                                        <x-listing-card
                                            :href="$isHotel ? route('HotelSingle', ['hotelslug' => $stay->IDs]) : route('HomeGuestSingle', ['homeguestslug' => $stay->IDs])"
                                            :image="Media::image($isHotel ? 'hotel' : 'guesthouse', $stay)"
                                            :title="$isHotel ? $stay->Place : $stay->GuestHouseName"
                                            :location="$stay->City"
                                            :rating="$stay->Rating"
                                            :price="$stay->Prices"
                                            unit="per night"
                                            :badge="$isHotel ? $stay->PlaceType : 'Home stay'">
                                            @if ($stay->BedBreakFast === 'Yes')<span class="tag"><i class="bi bi-cup-hot"></i>Breakfast</span>@endif
                                            @if (filled($stay->Parking))<span class="tag"><i class="bi bi-p-square"></i>Parking</span>@endif
                                        </x-listing-card>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">{{ $results->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

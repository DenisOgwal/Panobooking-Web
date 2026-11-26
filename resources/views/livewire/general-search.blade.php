@php
    use App\Support\Media;
    use Illuminate\Support\Str;
    $groups = collect([
        ['stays', 'Stays', 'bi-building', $hotelsearched],
        ['cars', 'Rental cars', 'bi-car-front', $carsearched],
        ['taxis', 'Airport taxis', 'bi-taxi-front', $taxisearched],
        ['ambulances', 'Ambulances', 'bi-heart-pulse', $ambulancesearched],
        ['tours', 'Tour packages', 'bi-compass', $tourpackagesearched],
        ['sites', 'Tourism sites', 'bi-camera', $tourismsites],
        ['events', 'Events', 'bi-calendar-event', $eventss],
    ]);
    $total = $groups->sum(fn ($g) => $g[3]->count());
@endphp
<div>
    <x-page-hero :title="$term !== '' ? 'Results for “' . $term . '”' : 'Search Panobooking'"
                 :subtitle="$total . ' ' . Str::plural('match', $total) . ' across stays, transport, tours and events'"
                 :crumbs="[['Search']]">
        <form class="mt-3 header-search search-wide input-icon" action="{{ route('generalSearchs') }}" method="GET" role="search">
            <i class="bi bi-search" aria-hidden="true"></i>
            <label class="visually-hidden" for="gs-term">Search</label>
            <input id="gs-term" class="form-control form-control-lg rounded-pill" type="search" name="salessearch" value="{{ $term }}" placeholder="Try a town, hotel, car or activity">
        </form>
    </x-page-hero>

    <div class="container-xl">
        <nav class="d-flex flex-wrap gap-2 py-3" aria-label="Result types">
            @foreach ($groups as [$key, $label, $icon, $items])
                <a @class(['btn btn-sm btn-icon', 'btn-soft' => $items->isNotEmpty(), 'btn-ghost text-muted' => $items->isEmpty()]) href="#{{ $key }}">
                    <i class="bi {{ $icon }}"></i>{{ $label }} <span class="badge rounded-pill text-bg-light">{{ $items->count() }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    @if ($total === 0)
        <section class="section-sm">
            <div class="container-xl">
                <x-empty-state title="Nothing matched “{{ $term }}”">Check the spelling, or try a town such as Kampala, Jinja or Entebbe.</x-empty-state>
            </div>
        </section>
    @endif

    @foreach ($groups as [$key, $label, $icon, $items])
        @continue($items->isEmpty())
        <section id="{{ $key }}" class="section-sm">
            <div class="container-xl">
                <x-section-heading :title="$label" />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($items as $item)
                        <div class="col">
                            @switch($key)
                                @case('stays')
                                    <x-listing-card :href="route('HotelSingle', ['hotelslug' => $item->IDs])" :image="Media::image('hotel', $item)"
                                        :title="$item->Place" :location="$item->City" :rating="$item->Rating" :price="$item->Prices" unit="per night" :badge="$item->PlaceType" />
                                    @break
                                @case('cars')
                                    @include('livewire.partials.vehicle-card', ['vehicle' => $item, 'type' => 'car'])
                                    @break
                                @case('taxis')
                                    @include('livewire.partials.vehicle-card', ['vehicle' => $item, 'type' => 'taxi'])
                                    @break
                                @case('ambulances')
                                    @include('livewire.partials.vehicle-card', ['vehicle' => $item, 'type' => 'ambulance'])
                                    @break
                                @case('tours')
                                    <x-listing-card :href="route('TourPackageDetails', ['searchslug' => $item->IDs])" :image="Media::image('tour', $item)"
                                        :title="$item->PackageName" :location="$item->City" :price="$item->PackageFees2" price-prefix="" unit="per person"
                                        :badge="$item->TripDays ? $item->TripDays . ' days' : null" />
                                    @break
                                @case('sites')
                                    <x-listing-card :href="route('TourismSiteDetails', ['searchslug' => $item->ID])" :image="Media::image('site', $item)"
                                        :title="$item->SiteName" :location="$item->City" cta="Explore">{{ Str::limit($item->ShortDescription, 70) }}</x-listing-card>
                                    @break
                                @case('events')
                                    <x-listing-card :href="route('EventsDetails', ['searchslug' => $item->ID])" :image="Media::image('event', $item)"
                                        :title="$item->EventName" :location="$item->City" cta="Details"
                                        :badge="\Illuminate\Support\Carbon::parse($item->StartDate)->format('j M')">{{ Str::limit($item->ShortDescription, 70) }}</x-listing-card>
                                    @break
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
</div>

@php use App\Support\Media; @endphp
<div>
    <x-page-hero :title="$heading ?? ($city !== '' ? 'Travel in ' . $city : 'Destinations')"
                 :subtitle="$subheading ?? 'Stays, cars, airport taxis and ambulances, all in one place.'"
                 :crumbs="$crumbs ?? array_filter([['Regions', '/Regions'], $country !== '' ? [$country, '/Regions'] : null, [$city ?: 'City']])" />

    @php
        $sections = [
            ['Stays', 'bi-building', $hotels, 'hotel'],
            ['Rental cars', 'bi-car-front', $cars, 'car'],
            ['Airport taxis', 'bi-taxi-front', $taxis, 'taxi'],
            ['Ambulances', 'bi-heart-pulse', $ambulances, 'ambulance'],
        ];
    @endphp

    <div class="container-xl">
        <nav class="d-flex flex-wrap gap-2 py-3" aria-label="Sections">
            @foreach ($sections as [$label, $icon, $items, $key])
                <a class="btn btn-soft btn-sm btn-icon" href="#{{ $key }}"><i class="bi {{ $icon }}"></i>{{ $label }} <span class="badge text-bg-light">{{ $items->count() }}</span></a>
            @endforeach
        </nav>
    </div>

    @foreach ($sections as [$label, $icon, $items, $key])
        <section id="{{ $key }}" @class(['section-sm', 'bg-white border-top border-bottom' => $loop->even])>
            <div class="container-xl">
                <x-section-heading :title="$label" />
                @if ($items->isEmpty())
                    <x-empty-state :icon="$icon" :title="'No ' . \Illuminate\Support\Str::lower($label) . ' listed here yet'" />
                @else
                    <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                        @foreach ($items as $item)
                            <div class="col">
                                @if ($key === 'hotel')
                                    <x-listing-card :href="route('HotelSingle', ['hotelslug' => $item->IDs])" :image="Media::image('hotel', $item)"
                                        :title="$item->Place" :location="$item->City" :rating="$item->Rating" :price="$item->Prices" unit="per night" :badge="$item->PlaceType" />
                                @else
                                    @include('livewire.partials.vehicle-card', ['vehicle' => $item, 'type' => $key])
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endforeach
</div>

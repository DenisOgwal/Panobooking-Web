@php use App\Support\Media; @endphp
<div>
    <x-page-hero :title="$title" subtitle="Choose a city to see stays, cars, taxis and ambulances there."
                 :crumbs="array_filter([['Regions', '/Regions'], $region !== '' ? [$region] : ['Cities']])" />

    <section class="section">
        <div class="container-xl">
            @if ($cities->isEmpty())
                <x-empty-state icon="bi-geo" title="No cities found">
                    <a href="/Regions">Browse all regions</a>
                </x-empty-state>
            @else
                <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-4">
                    @foreach ($cities as $city)
                        @php $count = (int) ($stays[$city->City] ?? 0); @endphp
                        <div class="col">
                            <a class="destination-tile" href="{{ route('Properties', ['cityslug' => $city->City, 'countryslug' => $city->Country]) }}">
                                <img src="{{ Media::city($city->City) }}" alt="" loading="lazy" onerror="this.remove()">
                                <div class="tile-body">
                                    <h3>{{ $city->City }}</h3>
                                    <span>{{ $count }} {{ \Illuminate\Support\Str::plural('stay', $count) }} · {{ $city->Region }}, {{ $city->Country }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>

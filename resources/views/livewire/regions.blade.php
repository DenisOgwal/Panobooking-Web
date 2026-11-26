<div>
    <x-page-hero title="Explore by region" subtitle="Pick a region to see its cities and towns." :crumbs="[['Destinations', '/DestinationsList'], ['Regions']]" />

    <section class="section">
        <div class="container-xl">
            @forelse ($regions as $country => $list)
                <x-section-heading :title="$country" :class="$loop->first ? '' : 'mt-5'" />
                <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($list as $region)
                        <div class="col">
                            <a class="category-chip h-100" href="{{ route('Cities', ['cityslug' => $region->Region, 'countryslug' => $region->Country]) }}">
                                <span class="icon"><i class="bi bi-map"></i></span>
                                <span>{{ $region->Region }}<small>{{ $region->cities }} {{ \Illuminate\Support\Str::plural('city', $region->cities) }}</small></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @empty
                <x-empty-state icon="bi-map" title="No regions yet" />
            @endforelse
        </div>
    </section>
</div>

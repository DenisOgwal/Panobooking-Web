@php use App\Support\Media; use Illuminate\Support\Carbon; @endphp
<div>
    <x-page-hero title="Tour packages" subtitle="Guided trips with transport, stays and activities included." :crumbs="[['Tours']]" />
    <section class="section">
        <div class="container-xl">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <p class="m-0 text-muted"><strong class="text-body">{{ $results->total() }}</strong> upcoming {{ \Illuminate\Support\Str::plural('package', $results->total()) }}</p>
                <div class="d-flex align-items-center gap-2">
                    <label for="sort" class="small text-muted text-nowrap">Sort by</label>
                    <select id="sort" class="form-select form-select-sm" wire:model.live="sort">
                        @foreach ($sortOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                    </select>
                </div>
            </div>
            @if ($results->isEmpty())
                <x-empty-state icon="bi-compass" title="No upcoming packages">New trips are added regularly. Check back soon.</x-empty-state>
            @else
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3" wire:loading.class="opacity-50">
                    @foreach ($results as $p)
                        <div class="col" wire:key="tour-{{ $p->IDs }}">
                            <x-listing-card :href="route('TourPackageDetails', ['searchslug' => $p->IDs])" :image="Media::image('tour', $p)"
                                :title="$p->PackageName" :location="$p->City" :price="$p->PackageFees2" price-prefix="" unit="per person"
                                :badge="$p->TripDays ? $p->TripDays . ' days' : null" cta="Details">
                                <span class="tag"><i class="bi bi-calendar-event"></i>{{ Carbon::parse($p->TravelDate)->format('j M Y') }}</span>
                                <span class="tag"><i class="bi bi-building"></i>{{ $p->TravelCompany }}</span>
                            </x-listing-card>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $results->links() }}</div>
            @endif
        </div>
    </section>
</div>

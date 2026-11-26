@php use App\Support\Vehicles; use Illuminate\Support\Str; @endphp
<div>
    <x-page-hero :title="$title" :subtitle="$subtitle" :crumbs="$crumbs" />

    <div class="container-xl">
        <x-search-panel :active="$c['searchTab']" compact class="mb-4" />
    </div>

    <section class="section-sm pt-2">
        <div class="container-xl">
            <div class="row g-4">
                @if ($categories->isNotEmpty())
                    <aside class="col-lg-3">
                        <h2 class="h6 text-uppercase text-muted mb-3">Type</h2>
                        <div class="d-flex flex-row flex-lg-column gap-2 overflow-auto pb-2">
                            <a @class(['category-chip py-2 flex-shrink-0', 'active' => ! $activeCategory]) href="{{ Vehicles::listUrl($type) }}">
                                <span>All<small>{{ $categories->sum() }} listed</small></span>
                            </a>
                            @foreach ($categories as $name => $count)
                                <a @class(['category-chip py-2 flex-shrink-0', 'active' => $activeCategory === $name]) href="{{ Vehicles::listUrl($type, $name) }}">
                                    <span>{{ $name }}<small>{{ $count }} listed</small></span>
                                </a>
                            @endforeach
                        </div>
                    </aside>
                @endif

                <div @class(['col-lg-9' => $categories->isNotEmpty(), 'col-12' => $categories->isEmpty()])>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <p class="m-0 text-muted" aria-live="polite"><strong class="text-body">{{ $results->total() }}</strong> {{ Str::plural('result', $results->total()) }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <label for="sort" class="small text-muted text-nowrap">Sort by</label>
                            <select id="sort" class="form-select form-select-sm" wire:model.live="sort">
                                @foreach ($sortOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <div wire:loading.class="opacity-50" wire:target="sort,gotoPage,nextPage,previousPage">
                        @if ($results->isEmpty())
                            <x-empty-state :icon="$c['icon']" :title="'No ' . Str::lower($c['plural']) . ' found'">
                                Try another area, or <a href="{{ Vehicles::listUrl($type) }}">see them all</a>.
                            </x-empty-state>
                        @else
                            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-xl-3">
                                @foreach ($results as $vehicle)
                                    <div class="col" wire:key="v-{{ $vehicle->IDs }}">@include('livewire.partials.vehicle-card', ['vehicle' => $vehicle, 'type' => $type])</div>
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

@php
    use App\Support\Media;
    use Illuminate\Support\Carbon;
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $split = fn ($v) => collect(preg_split('/[,;\n]+/', (string) $v))->map(fn ($s) => trim($s))->filter()->values();
@endphp
<div>
    <section class="section-sm">
        <div class="container-xl">
            <nav aria-label="breadcrumb"><ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/TourPackages">Tours</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $p->PackageName }}</li>
            </ol></nav>
            <header class="listing-header d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
                <div>
                    <span class="eyebrow">{{ $p->TravelCompany }}</span>
                    <h1 class="mb-1">{{ $p->PackageName }}</h1>
                    <div class="meta">
                        <span><i class="bi bi-geo-alt"></i> {{ $p->City }}</span>
                        @if ($p->TravelDate)<span><i class="bi bi-calendar-event"></i> Departs {{ Carbon::parse($p->TravelDate)->format('l j F Y') }}</span>@endif
                    </div>
                </div>
                <x-price :amount="$p->PackageFees2" unit="per person" size="lg" />
            </header>
            <x-gallery :images="$gallery" :alt="$p->PackageName" :name="'tour-' . $p->IDs" />
        </div>
    </section>

    <section class="section-sm pt-0">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="detail-section">
                        <h2>Trip at a glance</h2>
                        <div class="fact-list">
                            @if ($p->TripDays)<div class="fact"><i class="bi bi-calendar-range"></i><span>{{ $p->TripDays }} days<small>Duration</small></span></div>@endif
                            @if ($p->TravelHours)<div class="fact"><i class="bi bi-clock"></i><span>{{ $p->TravelHours }}<small>Travel time</small></span></div>@endif
                            <div class="fact"><i class="bi bi-geo-alt"></i><span>{{ $p->City }}<small>Starting point</small></span></div>
                            <div class="fact"><i class="bi bi-building"></i><span>{{ $p->TravelCompany }}<small>Operator</small></span></div>
                        </div>
                    </div>
                    @foreach ([['Destinations visited', 'bi-signpost-2', $p->DestinationsVisted], ['What is included', 'bi-check2-square', $p->ServicesOffered], ['Extra activities', 'bi-stars', $p->ExtraActivites]] as [$heading, $icon, $value])
                        @if ($split($value)->isNotEmpty())
                            <div class="detail-section">
                                <h2>{{ $heading }}</h2>
                                <div class="tag-list">@foreach ($split($value) as $item)<span class="tag"><i class="bi {{ $icon }}"></i>{{ $item }}</span>@endforeach</div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <aside class="col-lg-4">
                    <div class="booking-box">
                        <x-price :amount="$p->PackageFees2" unit="per person" size="lg" />
                        @if ($loggedIn)
                            <form wire:submit="submitbooking" class="mt-3" novalidate>
                                <x-field label="Travellers" name="travellers" type="number" min="1" max="50" wire:model.live.debounce.300ms="travellers" icon="bi-people" />
                                <div class="total mt-0"><span>Total</span><x-price :amount="$this->total" /></div>
                                <button class="btn btn-accent w-100 mt-3" type="submit">
                                    <span wire:loading.remove wire:target="submitbooking">Reserve this trip</span>
                                    <span wire:loading wire:target="submitbooking"><span class="spinner-border spinner-border-sm me-2"></span>Reserving…</span>
                                </button>
                                <p class="small text-muted mt-2 mb-0">No payment now. The operator confirms your place.</p>
                            </form>
                        @else
                            <div class="mt-3">@include('livewire.partials.signin-card')</div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($more->isNotEmpty())
        <section class="section bg-white border-top">
            <div class="container-xl">
                <x-card-rail title="More tours">
                    @foreach ($more as $m)
                        <div class="swiper-slide">
                            <x-listing-card :href="route('TourPackageDetails', ['searchslug' => $m->IDs])" :image="Media::image('tour', $m)"
                                :title="$m->PackageName" :location="$m->City" :price="$m->PackageFees2" price-prefix="" unit="per person" :badge="$m->TripDays ? $m->TripDays . ' days' : null" />
                        </div>
                    @endforeach
                </x-card-rail>
            </div>
        </section>
    @endif
</div>

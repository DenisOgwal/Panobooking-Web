@php
    use App\Support\Features;
    use App\Support\Vehicles;
    $v = $vehicle;
    $isCar = $type === 'car';
    $isAmbulance = $type === 'ambulance';
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $name = $v->CarSpec ?: $v->TaxiNo;
    $category = $v->{$c['category']};

    $facts = array_filter([
        $isAmbulance ? null : ['bi-people', 'Passengers', $v->Passengers],
        $isAmbulance ? null : ['bi-suitcase2', 'Luggage', $v->Laggages],
        $isAmbulance ? null : ['bi-gear', 'Gearbox', $v->Automatic === 'Yes' ? 'Automatic' : ($v->Automatic ? 'Manual' : null)],
        $isAmbulance ? null : ['bi-door-closed', 'Doors', $v->Doors],
        ['bi-hash', 'Plate', $v->TaxiNo],
        ['bi-geo-alt', 'Based in', $v->CurrentLocation ?: $v->City],
        $isCar && $v->DriverAge ? ['bi-person-badge', 'Min. driver age', $v->DriverAge] : null,
    ], fn ($f) => $f && filled($f[2]));
@endphp
<div>
    <section class="section-sm">
        <div class="container-xl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ $c['landing'] }}">{{ $c['plural'] }}</a></li>
                    @if ($category)<li class="breadcrumb-item"><a href="{{ Vehicles::listUrl($type, $category) }}">{{ $category }}</a></li>@endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
                </ol>
            </nav>

            <header class="listing-header d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
                <div>
                    <span class="eyebrow">{{ $category ?: $c['label'] }}</span>
                    <h1 class="mb-1">{{ $name }}</h1>
                    <div class="meta">
                        <x-rating :value="$v->Ratings" chip />
                        <span><i class="bi bi-building"></i> {{ $v->Company ?: $v->DriverName }}</span>
                        @if ($v->Availability === 'Available')<span class="text-success"><i class="bi bi-check2-circle"></i> Available now</span>@endif
                    </div>
                </div>
                @unless ($isAmbulance)
                    <x-price :amount="$v->Price" :prefix="$isCar ? 'From' : null" :unit="$c['unit']" size="lg" />
                @endunless
            </header>

            <x-gallery :images="$gallery" :alt="$name" :name="$type . '-' . $v->IDs" />
        </div>
    </section>

    <section class="section-sm pt-0">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    @if ($facts)
                        <div class="detail-section">
                            <h2>Key facts</h2>
                            <div class="fact-list">
                                @foreach ($facts as [$icon, $label, $value])
                                    <div class="fact"><i class="bi {{ $icon }}"></i><span>{{ $value }}<small>{{ $label }}</small></span></div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (filled($v->Description))
                        <div class="detail-section">
                            <h2>About this {{ \Illuminate\Support\Str::lower($c['label']) }}</h2>
                            <p class="mb-0">{{ $v->Description }}</p>
                        </div>
                    @endif

                    @if ($extras)
                        <div class="detail-section">
                            <h2>{{ $isAmbulance ? 'Equipment on board' : 'Included features' }}</h2>
                            <x-amenity-list :features="$extras" show-missing />
                            @if (filled($notes))<p class="small text-muted mt-3 mb-0"><i class="bi bi-plus-circle me-1"></i>{{ $notes }}</p>@endif
                        </div>
                    @endif

                    @if ($isCar && $v->PricePerDay && (float) $v->PricePerDay !== (float) $v->Price)
                        <div class="detail-section">
                            <h2>Rates</h2>
                            <div class="summary-line"><span>Within Kampala</span><x-price :amount="$v->Price" unit="per day" /></div>
                            <div class="summary-line"><span>Upcountry</span><x-price :amount="$v->PricePerDay" unit="per day" /></div>
                        </div>
                    @endif
                </div>

                <aside class="col-lg-4">
                    <div class="booking-box">
                        @if ($isAmbulance)
                            <h2 class="h5"><i class="bi bi-heart-pulse text-danger me-1"></i>Request this ambulance</h2>
                            <p class="small text-muted">In a life-threatening emergency, call your local emergency number first.</p>
                        @else
                            <x-price :amount="$v->Price" :prefix="$isCar ? 'From' : null" :unit="$c['unit']" size="lg" />
                        @endif
                        @if (filled($v->TelephoneNumber))
                            <p class="small my-3"><i class="bi bi-telephone text-primary me-2"></i><a href="tel:{{ preg_replace('/\s+/', '', $v->TelephoneNumber) }}">{{ $v->TelephoneNumber }}</a></p>
                        @endif
                        @if ($loggedIn)
                            <a @class(['btn w-100 mt-2', 'btn-accent' => ! $isAmbulance, 'btn-danger' => $isAmbulance]) href="{{ Vehicles::bookUrl($type, $v) }}">
                                {{ $isAmbulance ? 'Request now' : ($isCar ? 'Rent this car' : 'Book this taxi') }}
                            </a>
                            <p class="small text-muted mt-2 mb-0">No payment now. The provider confirms first.</p>
                        @else
                            <div class="mt-3">@include('livewire.partials.signin-card')</div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section bg-white border-top">
            <div class="container-xl">
                <x-card-rail :title="'More ' . \Illuminate\Support\Str::lower($category ? $category . ' ' . $c['plural'] : $c['plural'])">
                    @foreach ($related as $other)
                        <div class="swiper-slide">@include('livewire.partials.vehicle-card', ['vehicle' => $other, 'type' => $type])</div>
                    @endforeach
                </x-card-rail>
            </div>
        </section>
    @endif
</div>

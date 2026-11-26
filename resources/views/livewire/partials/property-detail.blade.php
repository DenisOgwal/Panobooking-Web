{{--
    Detail page for a hotel or guest house.
    Expects: $property, $kind ('hotel'|'guesthouse'), $name, $gallery (urls), $rooms (collection),
             $fromPrice (UGX), $related (collection), $bookUrl (guest houses: whole-house booking URL)
--}}
@php
    use App\Support\Features;
    use App\Support\Media;
    use Illuminate\Support\Str;

    $isHotel = $kind === 'hotel';
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $p = $property;

    $popular = Features::enabled($p->popularamenities);
    $views = Features::enabled($p->roomwith);
    $description = $p->propertydescription->pluck('Description')->filter()->first() ?: $p->Description;

    $facilityGroups = collect([
        ['Bathroom', 'bi-droplet', $p->bathroomdetail],
        ['Bedroom', 'bi-moon-stars', $p->bedroomdetail],
        ['View', 'bi-binoculars', $p->viewdetail],
        ['Outdoors', 'bi-tree', $p->outdoordetail],
        ['Kitchen', 'bi-cup-hot', $p->kitchendetail],
        ['Food & drink', 'bi-egg-fried', $p->fooddetail],
        ['Parking', 'bi-p-square', $p->parkingdetail],
        ['Transport', 'bi-bus-front', $p->transportationdetail],
        ['Cleaning', 'bi-stars', $p->cleaningdetail],
        ['Business', 'bi-briefcase', $p->businessfacilitydetail],
        ['Safety & security', 'bi-shield-check', $p->safetyandsecuritydetail],
        ['Wellness', 'bi-flower1', $p->spadetail],
        ['Accessibility', 'bi-universal-access', $p->accessibilitydetail],
        ['Media & technology', 'bi-tv', $p->mediaandtechdetail],
    ])->map(fn ($g) => [$g[0], $g[1], $g[2]->pluck('Amenity')->filter()->unique()->values()])
      ->filter(fn ($g) => $g[2]->isNotEmpty());

    $surroundings = collect([
        ['Nearby', 'bi-signpost', $p->nearbyplacesdetail, 'Place'],
        ['Top attractions', 'bi-camera', $p->topattractionsdetail, 'Place'],
        ['Restaurants & cafés', 'bi-cup-straw', $p->nearbyrestaurantsdetail, 'Place'],
        ['Markets', 'bi-basket', $p->nearbymarketsdetail, 'Place'],
        ['Supermarkets', 'bi-cart', $p->nearbysupermarketsdetail, 'Place'],
        ['Natural beauty', 'bi-tree-fill', $p->naturalbeautydetail, 'Place'],
        ['Public transport', 'bi-bus-front', $p->publictransportdetail, 'TransportName'],
        ['Closest airports', 'bi-airplane', $p->closestairportdetail, 'Airport'],
    ])->filter(fn ($s) => $s[2]->isNotEmpty());

    $rules = $p->houserulesdetail;
@endphp

<div>
    <section class="section-sm">
        <div class="container-xl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    @if ($isHotel)
                        <li class="breadcrumb-item"><a href="{{ route('DestinationsList', ['destinationslug' => $p->City]) }}">{{ $p->City }}</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="/homeguests">Home stays</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
                </ol>
            </nav>

            <header class="listing-header d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
                <div>
                    <span class="eyebrow">{{ $isHotel ? ($p->PlaceType ?: 'Stay') : 'Home stay' }}</span>
                    <h1 class="mb-1">{{ $name }}</h1>
                    <div class="meta">
                        <x-rating :value="$p->Rating" chip />
                        <span><i class="bi bi-geo-alt"></i> {{ filled($p->ExactLocation) && Str::contains(Str::lower($p->ExactLocation), Str::lower((string) $p->City)) ? $p->ExactLocation : collect([$p->ExactLocation, $p->City])->filter()->implode(', ') }}</span>
                        @if ($p->BedBreakFast === 'Yes')<span><i class="bi bi-cup-hot"></i> Breakfast available</span>@endif
                    </div>
                </div>
                <div class="text-md-end">
                    <x-price :amount="$fromPrice" prefix="From" unit="per night" size="lg" />
                </div>
            </header>

            <x-gallery :images="$gallery" :alt="$name" :name="'property-' . $p->IDs" />
        </div>
    </section>

    <section class="section-sm pt-0">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    @if (filled($description) || $p->reasonsforstaydetail->isNotEmpty())
                        <div class="detail-section">
                            <h2>About this {{ $isHotel ? Str::lower($p->PlaceType ?: 'stay') : 'home stay' }}</h2>
                            @if (filled($description))<p class="mb-3">{{ $description }}</p>@endif
                            @if ($p->reasonsforstaydetail->isNotEmpty())
                                <div class="tag-list">
                                    @foreach ($p->reasonsforstaydetail as $reason)
                                        <span class="tag"><i class="bi bi-hand-thumbs-up"></i>{{ $reason->Reason }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($popular)
                        <div class="detail-section">
                            <h2>Most popular amenities</h2>
                            <x-amenity-list :features="$popular" />
                            @if ($views)
                                <p class="small text-muted mt-3 mb-0"><i class="bi bi-binoculars me-1"></i>Rooms with {{ Str::lower(implode(', ', array_keys($views))) }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($isHotel)
                        <div class="detail-section" id="rooms">
                            <h2>Available rooms</h2>
                            @forelse ($rooms as $room)
                                @php
                                    $f = $room->roomdetails;
                                    $beds = collect(['KingBed' => 'king bed', 'QueenBed' => 'queen bed', 'TwinBed' => 'twin beds'])
                                        ->filter(fn ($label, $col) => $f && Features::isOn($f->{$col}))->values();
                                    $roomViews = $f ? Features::enabled($f, ['PoolView', 'CityView', 'LakeView', 'GardensView']) : [];
                                    $roomFeatures = $f ? Features::enabled($f, [], ['KingBed', 'QueenBed', 'TwinBed', 'PoolView', 'CityView', 'LakeView', 'GardensView']) : [];
                                @endphp
                                <article class="room-card" wire:key="room-{{ $room->IDs }}">
                                    <div class="room-media">
                                        <img src="{{ Media::image('room', $room) }}" data-fallback="{{ Media::placeholder() }}" alt="{{ $room->RoomName }}" loading="lazy">
                                    </div>
                                    <div>
                                        <h3>{{ $room->RoomName }}</h3>
                                        <div class="tag-list mb-2">
                                            @foreach ($beds as $bed)<span class="tag"><i class="bi bi-moon"></i>1 {{ $bed }}</span>@endforeach
                                            @if ($f && filled($f->Feet))<span class="tag"><i class="bi bi-arrows-angle-expand"></i>{{ $f->Feet }}</span>@endif
                                            @foreach (array_keys($roomViews) as $view)<span class="tag"><i class="bi bi-image"></i>{{ $view }}</span>@endforeach
                                        </div>
                                        @if (filled($room->RoomDesc))<p class="small text-muted mb-2">{{ $room->RoomDesc }}</p>@endif
                                        @if ($roomFeatures)
                                            <details>
                                                <summary class="small fw-semibold text-primary">{{ count($roomFeatures) }} room features</summary>
                                                <x-amenity-list :features="$roomFeatures" class="mt-2 small" />
                                            </details>
                                        @endif
                                    </div>
                                    <div class="room-aside">
                                        <div>
                                            <x-price :amount="$room->Price" unit="per night" />
                                            @if (filled($room->BreakFast))<div class="small text-success mt-1"><i class="bi bi-cup-hot"></i> Breakfast {{ Str::lower($room->BreakFast) }}</div>@endif
                                            @if (filled($room->RefundPolicy))<div class="small text-muted mt-1">{{ $room->RefundPolicy }}</div>@endif
                                        </div>
                                        <div class="w-100">
                                            @if ((int) $room->NoOfRooms > 0)
                                                <div class="small text-muted mb-2">{{ $room->NoOfRooms }} left</div>
                                            @endif
                                            @if ($loggedIn)
                                                <a class="btn btn-accent w-100" href="{{ route('CompleteBooking', ['hotelfinalslug' => $room->IDs]) }}">Reserve</a>
                                            @else
                                                <a class="btn btn-outline-primary w-100" href="#book">Sign in to reserve</a>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <x-empty-state icon="bi-door-closed" title="No rooms listed yet">Contact the property for availability.</x-empty-state>
                            @endforelse
                        </div>
                    @endif

                    @if ($facilityGroups->isNotEmpty())
                        <div class="detail-section">
                            <h2>Facilities</h2>
                            <div class="amenity-groups">
                                @foreach ($facilityGroups as [$label, $icon, $items])
                                    <div>
                                        <h3><i class="bi {{ $icon }}"></i>{{ $label }}</h3>
                                        <ul>@foreach ($items as $item)<li>{{ $item }}</li>@endforeach</ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($rules || $p->childrenpolicydetail->isNotEmpty() || $p->childrenextrabeddetail->isNotEmpty())
                        <div class="detail-section">
                            <h2>House rules</h2>
                            <dl class="rule-list m-0">
                                @if ($rules?->CheckIn)<div><dt><i class="bi bi-box-arrow-in-right"></i>Check-in</dt><dd>{{ $rules->CheckIn }}. Photo ID required.</dd></div>@endif
                                @if ($rules?->CheckOut)<div><dt><i class="bi bi-box-arrow-right"></i>Check-out</dt><dd>{{ $rules->CheckOut }}</dd></div>@endif
                                @if ($rules?->PaymentCancelation)<div><dt><i class="bi bi-arrow-counterclockwise"></i>Cancellation</dt><dd>{{ $rules->PaymentCancelation }}</dd></div>@endif
                                @if ($p->childrenpolicydetail->isNotEmpty() || $p->childrenextrabeddetail->isNotEmpty())
                                    <div><dt><i class="bi bi-people"></i>Children &amp; beds</dt><dd>
                                        @foreach ($p->childrenpolicydetail as $policy)<div>{{ $policy->Policy }}</div>@endforeach
                                        @foreach ($p->childrenextrabeddetail as $bed)<div class="text-muted">{{ $bed->YearsRange }}: {{ $bed->ExtraCondition }}</div>@endforeach
                                    </dd></div>
                                @endif
                                @if ($rules?->AgeRestriction)<div><dt><i class="bi bi-person-badge"></i>Age</dt><dd>{{ $rules->AgeRestriction }}</dd></div>@endif
                                @if ($rules?->Pets)<div><dt><i class="bi bi-heart"></i>Pets</dt><dd>{{ $rules->Pets }}</dd></div>@endif
                                @if ($rules?->CardAcceptability)<div><dt><i class="bi bi-credit-card"></i>Payment</dt><dd>{{ $rules->CardAcceptability }}</dd></div>@endif
                            </dl>
                        </div>
                    @endif

                    @if ($p->restaurantsonsitedetail->isNotEmpty())
                        <div class="detail-section">
                            <h2>Dining on site</h2>
                            <div class="row g-3 row-cols-1 row-cols-md-2">
                                @foreach ($p->restaurantsonsitedetail as $r)
                                    <div class="col">
                                        <div class="fact h-100 d-block p-3 rounded-3 bg-canvas">
                                            <strong class="d-block mb-1"><i class="bi bi-shop text-primary me-1"></i>{{ $r->RestaurantName }}</strong>
                                            @if ($r->Food)<div class="small"><span class="text-muted">Food:</span> {{ $r->Food }}</div>@endif
                                            @if ($r->OpenFor)<div class="small"><span class="text-muted">Open for:</span> {{ $r->OpenFor }}</div>@endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($surroundings->isNotEmpty())
                        <div class="detail-section">
                            <h2>Surroundings</h2>
                            <div class="row g-4 row-cols-1 row-cols-md-2">
                                @foreach ($surroundings as [$label, $icon, $items, $col])
                                    <div class="col">
                                        <h3 class="h6"><i class="bi {{ $icon }} text-primary me-1"></i>{{ $label }}</h3>
                                        <ul class="nearby-list">
                                            @foreach ($items as $item)
                                                <li><span>{{ $item->{$col} }}@if ($col === 'TransportName' && $item->TransportType) <small class="text-muted">({{ $item->TransportType }})</small>@endif</span><span>{{ $item->Distance }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="col-lg-4">
                    <div class="booking-box" id="book">
                        <x-price :amount="$fromPrice" prefix="From" unit="per night" size="lg" />
                        <ul class="list-unstyled small text-muted my-3">
                            @if ($p->CreditCard === 'Yes')<li><i class="bi bi-credit-card text-primary me-2"></i>Cards accepted</li>@endif
                            @if ($p->TaxesInclusive === 'Yes')<li><i class="bi bi-receipt text-primary me-2"></i>Taxes included</li>@endif
                            @if ($p->Prepayment === 'No')<li><i class="bi bi-wallet2 text-primary me-2"></i>No prepayment needed</li>@endif
                            @if (filled($p->ContactNo))<li><i class="bi bi-telephone text-primary me-2"></i>{{ $p->ContactNo }}</li>@endif
                        </ul>
                        @if ($loggedIn)
                            @if ($isHotel)
                                <a class="btn btn-accent w-100" href="#rooms">Choose a room</a>
                            @else
                                <a class="btn btn-accent w-100" href="{{ $bookUrl }}">Book this home</a>
                            @endif
                            <p class="small text-muted mt-2 mb-0">You won't be charged yet. The host confirms your booking first.</p>
                        @else
                            @include('livewire.partials.signin-card')
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section bg-white border-top">
            <div class="container-xl">
                <x-card-rail :title="$isHotel ? 'You might also like' : 'More home stays'">
                    @foreach ($related as $other)
                        <div class="swiper-slide">
                            <x-listing-card
                                :href="$isHotel ? route('HotelSingle', ['hotelslug' => $other->IDs]) : route('HomeGuestSingle', ['homeguestslug' => $other->IDs])"
                                :image="Media::image($isHotel ? 'hotel' : 'guesthouse', $other)"
                                :title="$isHotel ? $other->Place : $other->GuestHouseName"
                                :location="$other->City" :rating="$other->Rating" :price="$other->Prices" unit="per night" />
                        </div>
                    @endforeach
                </x-card-rail>
            </div>
        </section>
    @endif
</div>

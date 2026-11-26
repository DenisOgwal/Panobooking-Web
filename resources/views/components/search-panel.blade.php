{{--
    Tabbed search for stays, cars, airport taxis and ambulances. Submits to the existing GET
    search routes with their original field names.
    $active: 'stays' | 'cars' | 'taxi' | 'ambulance'
--}}
@props(['active' => 'stays', 'compact' => false])
@php
    $destinations = \Illuminate\Support\Facades\Cache::remember('search.taxi-destinations', 600,
        fn () => \App\Models\TaxiTripModel::query()->distinct()->orderBy('Tos')->pluck('Tos')->filter()->values());
    $tabs = [
        'stays' => ['Stays', 'bi-building'],
        'cars' => ['Car rental', 'bi-car-front'],
        'taxi' => ['Airport taxi', 'bi-taxi-front'],
        'ambulance' => ['Ambulance', 'bi-heart-pulse'],
    ];
    $uid = 'sp' . substr(md5(uniqid('', true)), 0, 6);
@endphp

<div {{ $attributes->class(['search-panel', 'search-panel-compact' => $compact]) }}>
    <ul class="nav nav-pills" role="tablist">
        @foreach ($tabs as $key => [$label, $icon])
            <li class="nav-item" role="presentation">
                <button @class(['nav-link', 'active' => $active === $key]) id="{{ $uid }}-{{ $key }}-tab" data-bs-toggle="pill"
                        data-bs-target="#{{ $uid }}-{{ $key }}" type="button" role="tab" aria-controls="{{ $uid }}-{{ $key }}"
                        aria-selected="{{ $active === $key ? 'true' : 'false' }}">
                    <i class="bi {{ $icon }}" aria-hidden="true"></i>{{ $label }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        <div @class(['tab-pane fade', 'show active' => $active === 'stays']) id="{{ $uid }}-stays" role="tabpanel" aria-labelledby="{{ $uid }}-stays-tab">
            <form action="{{ route('HotelSearch') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label" for="{{ $uid }}-city">Where to?</label>
                    <div class="input-icon"><i class="bi bi-geo-alt"></i>
                        <input id="{{ $uid }}-city" class="form-control" name="from_city" value="{{ request('from_city') }}" placeholder="City, e.g. Jinja" required>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <label class="form-label" for="{{ $uid }}-rooms">Rooms</label>
                    <select id="{{ $uid }}-rooms" class="form-select" name="no_of_rooms">
                        @for ($i = 1; $i <= 10; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <label class="form-label" for="{{ $uid }}-adults">Adults</label>
                    <select id="{{ $uid }}-adults" class="form-select" name="no_of_adults">
                        @for ($i = 1; $i <= 10; $i++)<option value="{{ $i }}" @selected($i === 2)>{{ $i }}</option>@endfor
                    </select>
                </div>
                <div class="col-4 col-md-1">
                    <label class="form-label" for="{{ $uid }}-kids">Kids</label>
                    <select id="{{ $uid }}-kids" class="form-select" name="no_of_children">
                        @for ($i = 0; $i <= 10; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary btn-icon justify-content-center" type="submit"><i class="bi bi-search"></i>Search</button>
                </div>
            </form>
        </div>

        <div @class(['tab-pane fade', 'show active' => $active === 'cars']) id="{{ $uid }}-cars" role="tabpanel" aria-labelledby="{{ $uid }}-cars-tab">
            <form action="{{ route('CarSearch') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label" for="{{ $uid }}-pickup">Pick-up location</label>
                    <div class="input-icon"><i class="bi bi-geo-alt"></i>
                        <input id="{{ $uid }}-pickup" class="form-control" name="pickuplocation" value="{{ request('pickuplocation') }}" placeholder="e.g. Kampala" required>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="{{ $uid }}-pdate">Pick-up date</label>
                    <input id="{{ $uid }}-pdate" class="form-control" type="date" name="PickupDate" min="{{ now()->toDateString() }}">
                </div>
                <div class="col-3 col-md-2">
                    <label class="form-label" for="{{ $uid }}-days">Days</label>
                    <input id="{{ $uid }}-days" class="form-control" type="number" min="1" name="Duration" value="1">
                </div>
                <div class="col-3 col-md-1">
                    <label class="form-label" for="{{ $uid }}-cars">Cars</label>
                    <input id="{{ $uid }}-cars" class="form-control" type="number" min="1" max="15" name="NoofCars" value="1">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary btn-icon justify-content-center" type="submit"><i class="bi bi-search"></i>Find cars</button>
                </div>
            </form>
        </div>

        <div @class(['tab-pane fade', 'show active' => $active === 'taxi']) id="{{ $uid }}-taxi" role="tabpanel" aria-labelledby="{{ $uid }}-taxi-tab">
            <form action="{{ route('TaxiSearch') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label" for="{{ $uid }}-dir">Trip</label>
                    <select id="{{ $uid }}-dir" class="form-select" name="pickuppoint">
                        <option value="From Airport">From the airport</option>
                        <option value="To Airport">To the airport</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label" for="{{ $uid }}-airport">Airport</label>
                    <select id="{{ $uid }}-airport" class="form-select" name="airport"><option value="Entebbe">Entebbe</option></select>
                </div>
                <div class="col-md-5">
                    <label class="form-label" for="{{ $uid }}-dest">Destination</label>
                    <select id="{{ $uid }}-dest" class="form-select" name="Destination">
                        @forelse ($destinations as $d)
                            <option value="{{ $d }}" @selected(request('Destination') === $d)>{{ $d }}</option>
                        @empty
                            <option value="">No destinations yet</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary btn-icon justify-content-center" type="submit"><i class="bi bi-search"></i>Find a taxi</button>
                </div>
            </form>
        </div>

        <div @class(['tab-pane fade', 'show active' => $active === 'ambulance']) id="{{ $uid }}-ambulance" role="tabpanel" aria-labelledby="{{ $uid }}-ambulance-tab">
            <form action="{{ route('AmbulanceSearch') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label class="form-label" for="{{ $uid }}-area">Where do you need an ambulance?</label>
                    <div class="input-icon"><i class="bi bi-geo-alt"></i>
                        <input id="{{ $uid }}-area" class="form-control" name="Area" value="{{ request('Area') }}" placeholder="Area or town, e.g. Kampala" required>
                    </div>
                    <div class="form-text"><i class="bi bi-telephone"></i> In an emergency, call your local emergency number first.</div>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-accent btn-icon justify-content-center" type="submit"><i class="bi bi-search"></i>Find help</button>
                </div>
            </form>
        </div>
    </div>
</div>

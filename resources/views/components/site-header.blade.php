@php
    $auth = app(\App\Support\CustomerAuth::class);
    $nav = [
        ['/', 'Stays', 'bi-building', ['/', 'HotelSearch*', 'HotelSingle*', 'DestinationsList*', 'homeguests*', 'HomeGuest*', 'CompleteBooking*', 'Properties*', 'PropertyTypes*', 'Regions*', 'Cities*']],
        ['/TourPackages', 'Tours', 'bi-compass', ['TourPackage*', 'TourismSite*', 'TourismProperties*', 'Events*']],
        ['/taxis', 'Airport taxi', 'bi-taxi-front', ['taxis*', 'Taxi*', 'CompleteTaxiBooking*']],
        ['/cars', 'Car rental', 'bi-car-front', ['cars*', 'Car*', 'CompleteCarBooking*']],
        ['/ambulance', 'Ambulance', 'bi-heart-pulse', ['ambulance*', 'Ambulance*', 'CompleteAmbulanceBooking*']],
        ['/blog', 'Blog', 'bi-journal-text', ['blog*', 'BlogDetail*']],
    ];
    $isActive = fn (array $patterns) => collect($patterns)->contains(fn ($p) => $p === '/' ? request()->is('/') : request()->is($p));
    $initials = collect(explode(' ', (string) $auth->name()))->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
@endphp

<header class="site-header">
    <nav class="navbar navbar-expand-xl" aria-label="Main">
        <div class="container-xl gap-2">
            <a class="navbar-brand me-xl-4" href="/">
                <img src="{{ asset('images/panologo2.png') }}" alt="Panobooking" width="150" height="45">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#site-nav"
                    aria-controls="site-nav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2" aria-hidden="true"></i>
            </button>

            <div class="collapse navbar-collapse" id="site-nav">
                <ul class="navbar-nav me-auto">
                    @foreach ($nav as [$href, $label, $icon, $patterns])
                        <li class="nav-item">
                            <a class="nav-link @if ($isActive($patterns)) active @endif" href="{{ $href }}" @if ($isActive($patterns)) aria-current="page" @endif>
                                <i class="bi {{ $icon }}" aria-hidden="true"></i>{{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center gap-2 mt-2 mt-xl-0">
                    <form class="header-search input-icon" action="{{ route('generalSearchs') }}" method="GET" role="search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <label class="visually-hidden" for="header-search">Search Panobooking</label>
                        <input id="header-search" class="form-control" type="search" name="salessearch"
                               value="{{ request()->routeIs('generalSearchs') ? request('salessearch') : '' }}"
                               placeholder="Search stays, cars, taxis…">
                    </form>

                    <livewire:currency-switcher />

                    <div class="dropdown">
                        <button class="btn btn-ghost btn-icon w-100 justify-content-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus-circle" aria-hidden="true"></i> List with us
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/RegisterProperty"><i class="bi bi-building-add me-2"></i>List a property</a></li>
                            <li><a class="dropdown-item" href="/RegisterCars"><i class="bi bi-car-front me-2"></i>Register a car</a></li>
                            <li><a class="dropdown-item" href="/RegisterAmbulance"><i class="bi bi-heart-pulse me-2"></i>Add an ambulance</a></li>
                        </ul>
                    </div>

                    @if ($auth->check())
                        <div class="dropdown">
                            <button class="btn btn-ghost account-btn w-100 justify-content-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar" aria-hidden="true">{{ $initials ?: '?' }}</span>
                                <span class="account-name text-truncate">{{ $auth->name() }}</span>
                                <i class="bi bi-chevron-down small" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/PendingBookings"><i class="bi bi-hourglass-split me-2"></i>Pending bookings</a></li>
                                <li><a class="dropdown-item" href="/Bookings"><i class="bi bi-clock-history me-2"></i>Booking history</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('clientlogout') }}"><i class="bi bi-box-arrow-right me-2"></i>Log out</a></li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-ghost" href="/Login">Sign in</a>
                        <a class="btn btn-primary" href="/Register">Create account</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>

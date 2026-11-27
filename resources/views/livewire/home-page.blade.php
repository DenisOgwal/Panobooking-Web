@php use App\Support\Media; @endphp
<div>
    {{-- Hero ------------------------------------------------------------- --}}
    <section class="hero">
        <div class="container-xl">
            <div class="row align-items-center g-4 mb-4 mb-lg-5">
                <div class="col-lg-7">
                    @if ($offer && $offer->hotel)
                        <a class="hero-offer mb-4" href="{{ route('CompleteBooking', ['hotelfinalslug' => $offer->IDs]) }}">
                            <span class="badge rounded-pill">Offer</span>
                            {{ $offer->RoomName }} at {{ $offer->hotel->Place }} from {{ app(\App\Support\Currency::class)->format($offer->Price) }}
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    @endif
                    <h1 class="mb-3">Find your next stay, ride or adventure in Uganda</h1>
                    <p class="lead mb-0">Hotels and home stays, rental cars, airport transfers, ambulances and guided tours, all in one place.</p>
                </div>
                <div class="col-lg-5">
                    <div class="hero-stats justify-content-lg-end">
                        <div><strong>{{ $stats['stays'] }}+</strong><span>verified stays</span></div>
                        <div><strong>{{ $stats['cars'] }}+</strong><span>cars for hire</span></div>
                        <div><strong>{{ $stats['cities'] }}</strong><span>cities</span></div>
                    </div>
                </div>
            </div>
            <x-search-panel active="stays" />
        </div>
    </section>

    {{-- Trending -------------------------------------------------------- --}}
    @if ($trending->isNotEmpty())
        <section class="section">
            <div class="container-xl">
                <x-card-rail eyebrow="Popular right now" title="Trending stays" subtitle="The places travellers are booking this week.">
                    @foreach ($trending as $hotel)
                        <div class="swiper-slide">
                            <x-listing-card
                                :href="route('HotelSingle', ['hotelslug' => $hotel->IDs])"
                                :image="Media::image('hotel', $hotel)"
                                :title="$hotel->Place"
                                :location="$hotel->City"
                                :rating="$hotel->Rating"
                                :price="$hotel->Prices"
                                unit="per night"
                                :badge="$hotel->PlaceType" />
                        </div>
                    @endforeach
                </x-card-rail>
            </div>
        </section>
    @endif

    {{-- Property types --------------------------------------------------- --}}
    <section class="section-sm">
        <div class="container-xl">
            <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-xl-6">
                @foreach ([['Hotel', 'Hotels', 'bi-building'], ['Apartment', 'Apartments', 'bi-buildings'], ['Lodge', 'Lodges', 'bi-tree'],
                           ['Resort', 'Resorts', 'bi-umbrella'], ['Cottage', 'Cottages', 'bi-house-heart'], ['Guest House', 'Guest houses', 'bi-house-door']] as [$type, $label, $icon])
                    <div class="col">
                        <a class="category-chip" href="{{ route('PropertyTypes', ['propertyslug' => $type]) }}">
                            <span class="icon"><i class="bi {{ $icon }}"></i></span>
                            <span>{{ $label }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Destinations ------------------------------------------------------ --}}
    {{-- The original Panobooking mosaic: five cities, the blue "Most Popular Destinations" tile, five more. --}}
    @if ($destinations->isNotEmpty())
        <section class="district-mosaic" aria-labelledby="districts-title">
            <ul class="district-grid">
                @foreach ([$destinations->take(5), null, $destinations->slice(5)] as $group)
                    @if ($group === null)
                        <li class="district-title">
                            <h2 id="districts-title">Most Popular Destinations</h2>
                            <a href="/DestinationsList">View all destinations</a>
                        </li>
                    @else
                        @foreach ($group as $destination)
                            <li>
                                <a class="district-tile" href="{{ route('DestinationsList', ['destinationslug' => $destination->City]) }}">
                                    <img src="{{ Media::city($destination->City) }}" alt="" loading="lazy" onerror="this.remove()">
                                    <span class="district-name">{{ $destination->City }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Featured ---------------------------------------------------------- --}}
    @if ($featured->isNotEmpty())
        <section class="section bg-white border-top border-bottom">
            <div class="container-xl">
                <x-card-rail eyebrow="Hand-picked" title="Featured properties" subtitle="Great locations, great service, great value.">
                    @foreach ($featured as $hotel)
                        <div class="swiper-slide">
                            <x-listing-card
                                :href="route('HotelSingle', ['hotelslug' => $hotel->IDs])"
                                :image="Media::image('hotel', $hotel)"
                                :title="$hotel->Place"
                                :location="$hotel->City"
                                :rating="$hotel->Rating"
                                :price="$hotel->Prices"
                                unit="per night"
                                badge="Featured"
                                badge-accent />
                        </div>
                    @endforeach
                </x-card-rail>
            </div>
        </section>
    @endif

    {{-- Services ---------------------------------------------------------- --}}
    <section class="section">
        <div class="container-xl">
            <x-section-heading center eyebrow="More than a bed" title="Everything for the trip" subtitle="Book the ride and the experiences alongside your stay." />
            <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-4">
                @foreach ([
                    ['/cars', 'bi-car-front', 'Car rental', 'Self-drive or with a driver, from saloons to 4x4s.'],
                    ['/taxis', 'bi-taxi-front', 'Airport taxi', 'Fixed-price transfers from Entebbe International.'],
                    ['/ambulance', 'bi-heart-pulse', 'Ambulance', 'Request an equipped ambulance near you.'],
                    ['/TourPackages', 'bi-compass', 'Tour packages', 'Gorilla treks, rafting, safaris and more.'],
                ] as [$href, $icon, $title, $text])
                    <div class="col">
                        <a class="category-chip h-100 align-items-start" href="{{ $href }}">
                            <span class="icon"><i class="bi {{ $icon }}"></i></span>
                            <span>{{ $title }}<small>{{ $text }}</small></span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tourism sites & events --------------------------------------------- --}}
    @if ($sites->isNotEmpty() || $events->isNotEmpty())
        <section class="section bg-white border-top border-bottom">
            <div class="container-xl">
                <div class="row g-4 g-lg-5">
                    @if ($sites->isNotEmpty())
                        <div class="col-lg-7">
                            <x-section-heading eyebrow="See and do" title="Tourism sites">
                                <x-slot:actions><a class="btn btn-soft btn-sm" href="/TourismSites">View all</a></x-slot:actions>
                            </x-section-heading>
                            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-3">
                                @foreach ($sites->take(3) as $site)
                                    <div class="col">
                                        <x-listing-card
                                            :href="route('TourismSiteDetails', ['searchslug' => $site->ID])"
                                            :image="Media::image('site', $site)"
                                            :title="$site->SiteName"
                                            :location="$site->City"
                                            cta="Explore">
                                            {{ \Illuminate\Support\Str::limit($site->ShortDescription, 70) }}
                                        </x-listing-card>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if ($events->isNotEmpty())
                        <div class="col-lg-5">
                            <x-section-heading eyebrow="What's on" title="Upcoming events">
                                <x-slot:actions><a class="btn btn-soft btn-sm" href="/Events">All events</a></x-slot:actions>
                            </x-section-heading>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($events as $event)
                                    @php $start = \Illuminate\Support\Carbon::parse($event->StartDate); @endphp
                                    <a class="category-chip" href="{{ route('EventsDetails', ['searchslug' => $event->ID]) }}">
                                        <span class="icon flex-column lh-1 text-center">
                                            <small class="text-uppercase fw-bold">{{ $start->format('M') }}</small>
                                            <span class="fw-bold">{{ $start->format('j') }}</span>
                                        </span>
                                        <span>{{ $event->EventName }}<small>{{ $event->City }} · {{ \Illuminate\Support\Str::limit($event->ShortDescription, 60) }}</small></span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Blog --------------------------------------------------------------- --}}
    @if ($articles->isNotEmpty())
        <section class="section">
            <div class="container-xl">
                <x-section-heading eyebrow="Travel guides" title="From the blog">
                    <x-slot:actions><a class="btn btn-soft" href="/blog">Read more</a></x-slot:actions>
                </x-section-heading>
                <div class="row g-4 row-cols-1 row-cols-md-3">
                    @foreach ($articles as $article)
                        <div class="col">
                            <article class="blog-card">
                                <div class="listing-media">
                                    <img src="{{ Media::image('blog', $article) }}" data-fallback="{{ Media::placeholder() }}" alt="" loading="lazy">
                                </div>
                                <div class="listing-body">
                                    <div class="listing-meta">
                                        <i class="bi bi-calendar3"></i>{{ \Illuminate\Support\Carbon::parse($article->Date)->format('j M Y') }}
                                        <span aria-hidden="true">·</span>
                                        <i class="bi bi-chat"></i>{{ $article->approved_comments_count }}
                                    </div>
                                    <h3 class="listing-title"><a href="{{ route('BlogDetail', ['blogslug' => $article->id]) }}">{{ $article->BlogTitle }}</a></h3>
                                    <p class="excerpt mb-0">{{ $article->BlogShortDescription }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials + feedback form ---------------------------------------- --}}
    <section class="section bg-white border-top">
        <div class="container-xl">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-7">
                    <x-section-heading eyebrow="Reviews" title="What travellers say" />
                    @if ($testimonials->isNotEmpty())
                        <div class="row g-3 row-cols-1 row-cols-md-2">
                            @foreach ($testimonials as $t)
                                <div class="col">
                                    <figure class="testimonial m-0">
                                        <blockquote>“{{ $t->FeedBack }}”</blockquote>
                                        <figcaption class="who">
                                            <span class="avatar" aria-hidden="true">{{ mb_substr($t->ClientName, 0, 1) }}</span>
                                            <span><strong>{{ $t->ClientName }}</strong><small>{{ collect([$t->ClientJob, $t->ClientCompany])->filter()->implode(', ') }}</small></span>
                                        </figcaption>
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-empty-state icon="bi-chat-heart" title="No reviews yet">Be the first to share your experience.</x-empty-state>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="form-card">
                        <h2 class="h4">Share your experience</h2>
                        <p class="text-muted">Travelled with us? Tell others how it went.</p>
                        <form wire:submit="submitfeedback" novalidate>
                            <div class="row g-0 column-gap-3">
                                <div class="col"><x-field label="Your name" name="clientname" wire:model.blur="clientname" autocomplete="name" /></div>
                                <div class="col"><x-field label="Email" name="clientemail" type="email" wire:model.blur="clientemail" autocomplete="email" /></div>
                            </div>
                            <div class="row g-0 column-gap-3">
                                <div class="col"><x-field label="Job title" name="clientjob" wire:model="clientjob" /></div>
                                <div class="col"><x-field label="Company" name="clientcompany" wire:model.blur="clientcompany" /></div>
                            </div>
                            <x-field label="Your review" name="clientfeedback" textarea rows="3" wire:model.blur="clientfeedback" />
                            <button class="btn btn-primary w-100" type="submit">
                                <span wire:loading.remove wire:target="submitfeedback">Send review</span>
                                <span wire:loading wire:target="submitfeedback"><span class="spinner-border spinner-border-sm me-2"></span>Sending…</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

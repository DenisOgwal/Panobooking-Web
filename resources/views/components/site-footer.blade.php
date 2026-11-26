{{-- $footerProperties and $footerArticles come from App\View\Composers\FooterComposer (cached). --}}
<footer class="site-footer">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-lg-4">
                <img class="footer-logo mb-3" src="{{ asset('images/panologo.png') }}" alt="Panobooking" width="150" height="45">
                <p class="mb-3">Stays, car rental, airport transfers, ambulances and tours across Uganda, booked in minutes.</p>
                <div class="social d-flex gap-2">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <h3>Explore</h3>
                <ul>
                    <li><a href="/">Hotels</a></li>
                    <li><a href="/homeguests">Home stays</a></li>
                    <li><a href="/TourPackages">Tour packages</a></li>
                    <li><a href="/TourismSites">Tourism sites</a></li>
                    <li><a href="/Events">Events</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <h3>Destinations</h3>
                <ul>
                    <li><a href="/Regions">Regions</a></li>
                    <li><a href="/Cities">Cities</a></li>
                    <li><a href="/DestinationsList">All destinations</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <h3>Popular stays</h3>
                <ul>
                    @forelse ($footerProperties ?? [] as $property)
                        <li><a href="{{ route('HotelSingle', ['hotelslug' => $property->IDs]) }}">{{ $property->Place }}</a></li>
                    @empty
                        <li><a href="/">Browse stays</a></li>
                    @endforelse
                </ul>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <h3>Help</h3>
                <ul>
                    <li><a href="/HelpCenter">Help centre</a></li>
                    <li><a href="/Faqs">FAQs</a></li>
                    <li><a href="/contactus">Contact us</a></li>
                    <li><a href="/privacypolicy">Privacy policy</a></li>
                    <li><a href="/TermsAndConditions">Terms &amp; conditions</a></li>
                </ul>
            </div>
        </div>

        @if (! empty($footerArticles) && count($footerArticles))
            <div class="mt-4">
                <h3>From the blog</h3>
                <ul class="d-flex flex-wrap gap-3 column-gap-4">
                    @foreach ($footerArticles as $article)
                        <li class="m-0"><a href="{{ route('BlogDetail', ['blogslug' => $article->id]) }}">{{ $article->BlogTitle }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} Panobooking. All rights reserved.</span>
            <span>Prices are shown in {{ app(\App\Support\Currency::class)->code() }}.</span>
        </div>
    </div>
</footer>

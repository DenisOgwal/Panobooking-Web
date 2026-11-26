<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /** Seed once when the test database is migrated; each test then runs in a rolled-back transaction. */
    protected $seed = true;


    public static function pages(): array
    {
        $urls = [
            '/', '/contactus', '/ambulance', '/cars', '/taxis', '/blog', '/homeguests',
            '/HotelSearch?from_city=Jinja', '/generalSearchs?salessearch=Jinja', '/CarSearch?pickuplocation=Kampala',
            '/TaxiSearch', '/AmbulanceSearch?Area=Kampala', '/HomeGuestSearch?Destination=Jinja',
            '/HotelSingle?hotelslug=1', '/BlogDetail?blogslug=1', '/DestinationsList', '/CarDetails?carslug=1',
            '/CarsList?carcategoriesslug=SUV', '/TaxisList', '/TaxiDetails?taxislug=1', '/AmbulanceDetails?ambulanceslug=1',
            '/AmbulanceList', '/HomeGuestsList', '/HomeGuestSingle?homeguestslug=1', '/Regions', '/Cities',
            '/Properties?cityslug=Jinja&countryslug=Uganda', '/PropertyTypes?propertyslug=Hotel', '/TourismSites',
            '/TourismProperties', '/TourismSiteDetails?searchslug=1', '/Events', '/EventsProperties', '/EventsDetails?searchslug=1',
            '/TourPackages', '/TourPackageDetails?searchslug=1', '/CompleteBooking?hotelfinalslug=2',
            '/CompleteCarBooking?carrentalfinalslug=1', '/CompleteTaxiBooking?taxifinalslug=1', '/CompleteAmbulanceBooking?ambulancefinalslug=1',
            '/CompleteHomeGuestBooking?homeguestfinalslug=1', '/BookingSuccess', '/Login', '/Register', '/RegisterProperty',
            '/RegisterCars', '/RegisterAmbulance', '/privacypolicy', '/TermsAndConditions', '/Faqs', '/HelpCenter',
        ];

        return array_combine($urls, array_map(fn ($u) => [$u], $urls));
    }

    #[DataProvider('pages')]
    public function test_page_renders(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public static function missingListings(): array
    {
        return [
            'hotel' => ['/HotelSingle?hotelslug=999'],
            'hotel without slug' => ['/HotelSingle'],
            'guest house' => ['/HomeGuestSingle?homeguestslug=999'],
            'car' => ['/CarDetails?carslug=999'],
            'taxi' => ['/TaxiDetails?taxislug=999'],
            'ambulance' => ['/AmbulanceDetails?ambulanceslug=999'],
        ];
    }

    #[DataProvider('missingListings')]
    public function test_missing_listing_returns_404(string $url): void
    {
        $this->get($url)->assertNotFound();
    }

    public function test_hotel_page_shows_rooms_and_amenities(): void
    {
        $this->get('/HotelSingle?hotelslug=1')
            ->assertOk()
            ->assertSee('Nile Breeze Hotel')
            ->assertSee('Deluxe Double')
            ->assertSee('The Terrace');
    }
}

<?php

namespace Tests\Feature;

use App\Livewire\CompleteBooking;
use App\Support\Currency;
use App\Support\CustomerAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class BookingAndCurrencyTest extends TestCase
{
    use RefreshDatabase;

    /** Seed once when the test database is migrated; each test then runs in a rolled-back transaction. */
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    private function asDemoCustomer(): void
    {
        session([CustomerAuth::SESSION_KEY => ['Email' => 'demo@panobooking.test', 'FullNames' => 'Demo Traveller']]);
    }

    public function test_room_booking_creates_a_pending_cart_row(): void
    {
        $this->asDemoCustomer();

        $in = now()->addDays(10)->toDateString();
        $out = now()->addDays(13)->toDateString();

        // Room 2 = Nile Breeze Hotel, Deluxe Double, UGX 252,000 / night
        Livewire::withQueryParams(['hotelfinalslug' => 2])
            ->test(CompleteBooking::class)
            ->set('checkindate', $in)
            ->set('checkoutdate', $out)
            ->set('Childrenno', 1)
            ->set('Destinationquantity', 2)
            ->assertSet('total', 2 * 3 * 252000)
            ->call('submitfeedback')
            ->assertHasNoErrors()
            ->assertRedirect('/BookingSuccess');

        $row = DB::table('cart')->where('User', 'demo@panobooking.test')->orderByDesc('IDs')->first();
        $this->assertSame('Deluxe Double', $row->ProductName);
        $this->assertSame('Nile Breeze Hotel', $row->Facility);
        $this->assertEquals(2, $row->Quantity);
        $this->assertEquals(2 * 3 * 252000, $row->Cost);
        $this->assertSame($in, $row->DatesFrom);   // plain dates, no time part
        $this->assertSame($out, $row->DatesTo);
        $this->assertSame('Has 1 Children', $row->Descriptions);
        $this->assertSame('Pending', $row->Confirmed);
        $this->assertNotNull($row->Date);
    }

    public function test_booking_validates_dates_and_room_count(): void
    {
        $this->asDemoCustomer();

        Livewire::withQueryParams(['hotelfinalslug' => 2]) // 6 Deluxe Doubles in the seed
            ->test(CompleteBooking::class)
            ->set('checkindate', now()->subDay()->toDateString())
            ->set('checkoutdate', now()->subDays(2)->toDateString())
            ->set('Destinationquantity', 99)
            ->call('submitfeedback')
            ->assertHasErrors(['checkindate', 'checkoutdate', 'Destinationquantity']);

        $this->assertSame(0, DB::table('cart')->where('Descriptions', 'like', 'Has%')->where('Quantity', 99)->count());
    }

    public function test_guests_must_sign_in_before_booking(): void
    {
        Livewire::withQueryParams(['hotelfinalslug' => 2])
            ->test(CompleteBooking::class)
            ->call('submitfeedback')
            ->assertHasErrors('clientemail')
            ->assertNoRedirect();
    }

    public function test_unknown_room_returns_404(): void
    {
        $this->get('/CompleteBooking?hotelfinalslug=999')->assertNotFound();
    }

    public function test_bookings_do_not_call_the_live_site_outside_production(): void
    {
        $this->asDemoCustomer();

        Livewire::withQueryParams(['hotelfinalslug' => 2])
            ->test(CompleteBooking::class)
            ->set('checkindate', now()->addDay()->toDateString())
            ->set('checkoutdate', now()->addDays(2)->toDateString())
            ->set('Destinationquantity', 1)
            ->call('submitfeedback');

        Http::assertNothingSent();
    }

    public function test_currency_conversion_matches_the_original_rounding(): void
    {
        $currency = app(Currency::class);

        $this->assertSame('UGX 180,000', $currency->format(180000, 'UGX'));
        $this->assertSame('USD 49', $currency->format(180000, 'USD'));   // 48.6 rounds up
        $this->assertSame('EUR 45', $currency->format(180000, 'EUR'));   // exactly 45
        $this->assertSame('UGX', $currency->code());                     // default
        $this->assertSame(['UGX', 'USD', 'EUR', 'KES'], $currency->available());
    }

    public function test_currency_choice_is_saved_for_logged_in_customers(): void
    {
        $this->asDemoCustomer();
        $currency = app(Currency::class);

        $currency->set('USD');
        $this->assertSame('USD', $currency->code());
        $this->assertSame('USD', DB::table('registration')->where('Email', 'demo@panobooking.test')->value('SelectedCurrency'));

        $currency->set('XYZ'); // unknown codes are ignored
        $this->assertSame('USD', $currency->code());
    }
}

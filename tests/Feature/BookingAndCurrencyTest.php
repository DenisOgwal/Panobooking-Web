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

        // Room 2 = Nile Breeze Hotel, Deluxe Double, UGX 252,000 / night
        Livewire::withQueryParams(['hotelfinalslug' => 2])
            ->test(CompleteBooking::class)
            ->set('checkindate', '2026-12-10')
            ->set('checkoutdate', '2026-12-13')
            ->set('Childrenno', 1)
            ->set('Destinationquantity', 2)
            ->call('submitfeedback')
            ->assertRedirect('/BookingSuccess');

        $row = DB::table('cart')->where('User', 'demo@panobooking.test')->orderByDesc('IDs')->first();
        $this->assertSame('Deluxe Double', $row->ProductName);
        $this->assertSame('Nile Breeze Hotel', $row->Facility);
        $this->assertEquals(2, $row->Quantity);
        $this->assertEquals(2 * 3 * 252000, $row->Cost);
        $this->assertSame('Pending', $row->Confirmed);
        $this->assertNotNull($row->Date);
    }

    public function test_bookings_do_not_call_the_live_site_outside_production(): void
    {
        $this->asDemoCustomer();

        Livewire::withQueryParams(['hotelfinalslug' => 2])
            ->test(CompleteBooking::class)
            ->set('checkindate', '2026-12-10')->set('checkoutdate', '2026-12-11')
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

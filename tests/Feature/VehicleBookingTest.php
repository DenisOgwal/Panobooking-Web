<?php

namespace Tests\Feature;

use App\Livewire\CompleteAmbulanceBooking;
use App\Livewire\CompleteCarRentalBooking;
use App\Livewire\CompleteTaxiBooking;
use App\Support\CustomerAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class VehicleBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        session([CustomerAuth::SESSION_KEY => ['Email' => 'demo@panobooking.test', 'FullNames' => 'Demo Traveller']]);
    }

    private function lastBooking(): object
    {
        return DB::table('cart')->where('User', 'demo@panobooking.test')->orderByDesc('IDs')->first();
    }

    public function test_car_rental_uses_the_upcountry_rate_when_chosen(): void
    {
        // Car 1 in the seed: UBA 101A, Price = PricePerDay = 250,000. Make the upcountry rate differ.
        DB::table('carrentals')->where('IDs', 1)->update(['PricePerDay' => 300000]);
        $date = now()->addDays(3)->toDateString();

        Livewire::withQueryParams(['carrentalfinalslug' => 1])
            ->test(CompleteCarRentalBooking::class)
            ->set('checkindate', $date)
            ->set('Duration', 3)
            ->set('Carsquantity', 2)
            ->set('driveloc', 'Upcountry')
            ->set('pickuppoint', 'Entebbe Airport')
            ->assertSet('total', 300000 * 2 * 3)
            ->call('book')
            ->assertHasNoErrors()
            ->assertRedirect('/BookingSuccess');

        $row = $this->lastBooking();
        $this->assertSame('Car Rental', $row->BookingType);
        $this->assertSame('UBA 101A', $row->ProductName);
        $this->assertEquals(1800000, $row->Cost);
        $this->assertSame($date, $row->DatesFrom);
        $this->assertSame(now()->addDays(6)->toDateString(), $row->DatesTo);
        $this->assertStringContainsString('Entebbe Airport', $row->Descriptions);
    }

    public function test_taxi_fare_comes_from_the_trip_table(): void
    {
        Livewire::withQueryParams(['taxifinalslug' => 1])
            ->test(CompleteTaxiBooking::class)
            ->set('Destination', 'Jinja')
            ->assertSet('fare', 250000.0)
            ->set('NoOfPeople', 3)
            ->call('book')
            ->assertHasNoErrors()
            ->assertRedirect('/BookingSuccess');

        $row = $this->lastBooking();
        $this->assertSame('Airport Taxi', $row->BookingType);
        $this->assertEquals(250000, $row->Cost);
        $this->assertStringContainsString('Entebbe Airport to Jinja', $row->Descriptions);
        $this->assertStringContainsString('NO. OF PEOPLE: 3', $row->Descriptions);
    }

    public function test_ambulance_request_needs_a_reason(): void
    {
        Livewire::withQueryParams(['ambulancefinalslug' => 1])
            ->test(CompleteAmbulanceBooking::class)
            ->set('pickuppoint', 'Main gate')
            ->set('reason', 'short')
            ->call('book')
            ->assertHasErrors('reason')
            ->set('reason', 'Patient transfer to the regional referral hospital')
            ->call('book')
            ->assertHasNoErrors()
            ->assertRedirect('/BookingSuccess');

        $row = $this->lastBooking();
        $this->assertSame('Ambulance', $row->BookingType);
        $this->assertEquals(0, $row->Cost);
        Http::assertNothingSent();
    }

    public function test_unknown_vehicles_return_404(): void
    {
        $this->get('/CompleteCarBooking?carrentalfinalslug=999')->assertNotFound();
        $this->get('/CompleteTaxiBooking?taxifinalslug=999')->assertNotFound();
        $this->get('/CompleteAmbulanceBooking?ambulancefinalslug=999')->assertNotFound();
    }
}

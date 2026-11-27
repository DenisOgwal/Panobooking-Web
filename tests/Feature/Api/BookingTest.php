<?php

namespace Tests\Feature\Api;

use App\Models\CartModel;
use App\Models\HomeGuestModel;
use App\Models\RoomsModel;
use App\Models\TourPackages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BookingTest extends ApiTestCase
{
    private function last(): CartModel
    {
        return CartModel::orderByDesc('IDs')->firstOrFail();
    }

    public function test_book_a_room_with_the_same_total_as_the_website(): void
    {
        $room = RoomsModel::firstOrFail();
        $in = now()->addDays(5)->toDateString();
        $out = now()->addDays(8)->toDateString();
        $input = ['check_in' => $in, 'check_out' => $out, 'rooms' => 2, 'children' => 1];
        $total = 3 * 2 * (float) $room->Price;

        // Quote and form defaults are public.
        $this->getJson($this->api("bookings/form/room/{$room->IDs}"))->assertOk()
            ->assertJsonPath('data.fields', ['check_in', 'check_out', 'rooms', 'children']);
        $quote = $this->postJson($this->api("bookings/quote/room/{$room->IDs}"), $input, ['X-Currency' => 'USD'])->assertOk()
            ->assertJsonPath('data.total.currency', 'USD')->assertJsonPath('data.nights', 3);
        $this->assertEquals($total, $quote->json('data.total.ugx'));

        $this->signIn();
        $booked = $this->postJson($this->api("bookings/room/{$room->IDs}"), $input, ['X-Currency' => 'USD'])->assertCreated()
            ->assertJsonPath('data.status', 'pending')->assertJsonPath('data.type', 'room')
            ->assertJsonPath('data.charged.currency', 'USD')->assertJsonPath('data.can_cancel', true);
        $this->assertEquals($total, $booked->json('data.cost.ugx'));

        $row = $this->last();
        $this->assertSame('demo@panobooking.test', $row->User);
        $this->assertSame('Stays', $row->BookingType);
        $this->assertSame($room->RoomName, $row->ProductName);
        $this->assertSame($in, $row->DatesFrom);
        $this->assertSame('Has 1 Children', $row->Descriptions);
        $this->assertSame('Pending', $row->Confirmed);
        $this->assertEquals(ceil($total / 3700), $row->CurrencyAmount);
    }

    public function test_booking_validation(): void
    {
        $room = RoomsModel::firstOrFail();
        $this->signIn();

        $this->postJson($this->api("bookings/room/{$room->IDs}"), ['check_in' => now()->subDay()->toDateString(), 'check_out' => now()->subDays(2)->toDateString(), 'rooms' => 999])
            ->assertUnprocessable()->assertJsonValidationErrors(['check_in', 'check_out', 'rooms']);
        $this->postJson($this->api('bookings/room/999999'), [])->assertNotFound();
        $this->postJson($this->api('bookings/spaceship/1'), [])->assertNotFound();
        $this->assertSame(0, CartModel::where('User', 'demo@panobooking.test')->where('Cost', '>', 999999999)->count());
    }

    public function test_book_every_other_type(): void
    {
        $this->signIn();
        config(['panobooking.notifications.enabled' => true]);
        DB::table('carrentals')->where('IDs', 1)->update(['PricePerDay' => 300000]);
        $house = HomeGuestModel::firstOrFail();
        $tour = TourPackages::upcoming()->firstOrFail();

        $cases = [
            ["home-stay/{$house->IDs}", ['check_in' => now()->addDay()->toDateString(), 'check_out' => now()->addDays(3)->toDateString(), 'notes' => 'Arriving late'],
                'Home Guest House', 2 * (float) $house->Prices],
            ['car/1', ['pickup_date' => now()->addDays(2)->toDateString(), 'days' => 3, 'cars' => 2, 'driving_area' => 'Upcountry', 'pickup_point' => 'Entebbe Airport'],
                'Car Rental', 300000 * 2 * 3],
            ['taxi/1', ['trip' => 'From Airport', 'airport' => 'Entebbe', 'destination' => 'Jinja', 'passengers' => 3, 'date' => now()->addDay()->toDateString(), 'time' => '09:30'],
                'Airport Taxi', 250000],
            ['ambulance/1', ['area' => 'Kampala', 'pickup_point' => 'Main gate', 'reason' => 'Patient transfer to the referral hospital', 'date' => now()->toDateString(), 'time' => '23:00'],
                'Ambulance', 0],
            ["tour/{$tour->IDs}", ['travellers' => 3], 'Tour Package', 3 * (float) $tour->PackageFees2],
        ];

        foreach ($cases as [$path, $input, $type, $total]) {
            $this->postJson($this->api("bookings/{$path}"), $input)->assertCreated();
            $row = $this->last();
            $this->assertSame($type, $row->BookingType, $path);
            $this->assertEquals($total, (float) $row->Cost, $path);
        }

        $this->assertStringContainsString('Entebbe Airport to Jinja', CartModel::where('BookingType', 'Airport Taxi')->latest('IDs')->value('Descriptions'));
        Http::assertSentCount(4); // car, taxi, ambulance and tour notify the provider; home stays don't
    }

    public function test_customers_list_view_and_cancel_only_their_own_bookings(): void
    {
        $mine = CartModel::create(['Facility' => 'X', 'ProductName' => 'Y', 'User' => 'demo@panobooking.test', 'UserNames' => 'Demo', 'Cost' => 1000]);
        $theirs = CartModel::create(['Facility' => 'X', 'ProductName' => 'Y', 'User' => 'other@example.test', 'UserNames' => 'Other', 'Cost' => 1000]);
        $confirmed = CartModel::where('User', 'demo@panobooking.test')->where('Confirmed', 'Confirmed')->firstOrFail();
        DB::table('cart')->where('IDs', $mine->IDs)->update(['Confirmed' => 'Pending']);
        DB::table('cart')->where('IDs', $theirs->IDs)->update(['Confirmed' => 'Pending']);
        $this->signIn();

        $pending = array_column($this->getJson($this->api('bookings?status=pending'))->assertOk()->json('data'), 'id');
        $this->assertContains($mine->IDs, $pending);
        $this->assertNotContains($theirs->IDs, $pending);
        $this->assertNotContains($confirmed->IDs, $pending);
        $this->assertContains($confirmed->IDs, array_column($this->getJson($this->api('bookings?status=history'))->json('data'), 'id'));

        $this->getJson($this->api("bookings/{$theirs->IDs}"))->assertNotFound();
        $this->deleteJson($this->api("bookings/{$theirs->IDs}"))->assertNotFound();
        $this->deleteJson($this->api("bookings/{$confirmed->IDs}"))->assertStatus(409);
        $this->deleteJson($this->api("bookings/{$mine->IDs}"))->assertOk();

        $this->assertDatabaseMissing('cart', ['IDs' => $mine->IDs]);
        $this->assertDatabaseHas('cart', ['IDs' => $theirs->IDs]);
        $this->assertDatabaseHas('cart', ['IDs' => $confirmed->IDs]);
    }
}

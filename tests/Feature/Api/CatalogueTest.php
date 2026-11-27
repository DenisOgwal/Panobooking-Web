<?php

namespace Tests\Feature\Api;

use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use Illuminate\Support\Facades\DB;

class CatalogueTest extends ApiTestCase
{
    public function test_meta_and_home(): void
    {
        $this->getJson($this->api('meta'))->assertOk()
            ->assertJsonStructure(['data' => ['currencies' => [['code', 'ugx_per_unit']], 'sort_options', 'property_types',
                'stay_cities', 'home_stay_cities', 'vehicle_categories' => ['car', 'taxi', 'ambulance'], 'taxi_airports', 'pages']]);

        $this->getJson($this->api('home'))->assertOk()
            ->assertJsonStructure(['data' => ['offer', 'trending', 'featured', 'destinations', 'tourism_sites', 'events',
                'articles', 'testimonials', 'stats' => ['stays', 'cars', 'cities']]]);
    }

    public function test_stays_list_filters_sorts_and_paginates(): void
    {
        $res = $this->getJson($this->api('stays?per_page=3'))->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'type', 'city', 'rating', 'price_from' => ['ugx', 'amount', 'currency', 'formatted'], 'photo']],
                'links', 'meta' => ['current_page', 'last_page', 'total']]);
        $this->assertCount(3, $res->json('data'));
        $this->assertSame(HotelsModel::approved()->count(), $res->json('meta.total'));

        $city = HotelsModel::approved()->value('City');
        foreach ($this->getJson($this->api('stays?city=' . urlencode($city)))->json('data') as $stay) {
            $this->assertSame($city, $stay['city']);
        }

        $prices = array_column(array_column($this->getJson($this->api('stays?sort=price_asc&per_page=50'))->json('data'), 'price_from'), 'ugx');
        $sorted = $prices;
        sort($sorted);
        $this->assertSame($sorted, $prices);
    }

    public function test_unapproved_listings_are_never_public(): void
    {
        $hotel = HotelsModel::firstOrFail();
        $car = RentalCarModel::firstOrFail();
        DB::table('pickuplocations')->where('IDs', $hotel->IDs)->update(['Approved' => 'Not Approved']);
        DB::table('carrentals')->where('IDs', $car->IDs)->update(['Approved' => null]);

        $this->getJson($this->api("stays/{$hotel->IDs}"))->assertNotFound()->assertExactJson(['message' => 'Not found.']);
        $this->assertNotContains($hotel->IDs, array_column($this->getJson($this->api('stays?per_page=50'))->json('data'), 'id'));
        $this->getJson($this->api("vehicles/car/{$car->IDs}"))->assertNotFound();
        $this->assertNotContains($car->IDs, array_column($this->getJson($this->api('search?q=' . urlencode($car->TaxiNo)))->json('data.cars'), 'id'));
    }

    public function test_stay_detail_has_rooms_amenities_and_related(): void
    {
        $hotel = HotelsModel::approved()->firstOrFail();

        $this->getJson($this->api("stays/{$hotel->IDs}"))->assertOk()
            ->assertJsonPath('data.name', $hotel->Place)
            ->assertJsonStructure(['data' => ['gallery', 'policies', 'coordinates', 'details' => ['popularamenities' => ['label', 'tab', 'items']],
                'rooms' => [['id', 'name', 'price', 'features', 'rooms_available']]], 'related']);

        $room = $hotel->rooms()->first();
        $this->getJson($this->api("rooms/{$room->IDs}"))->assertOk()->assertJsonPath('data.stay.name', $hotel->Place);
    }

    public function test_prices_follow_the_currency_header(): void
    {
        $hotel = HotelsModel::approved()->firstOrFail();

        $res = $this->getJson($this->api("stays/{$hotel->IDs}"), ['X-Currency' => 'USD'])->assertOk()
            ->assertHeader('X-Currency', 'USD')
            ->assertJsonPath('data.price_from.currency', 'USD');
        $this->assertEquals((float) $hotel->Prices, $res->json('data.price_from.ugx'));
        $this->assertEquals(ceil($hotel->Prices / 3700), $res->json('data.price_from.amount'));

        $this->getJson($this->api("stays/{$hotel->IDs}?currency=XYZ"))->assertJsonPath('data.price_from.currency', 'UGX');

        // A signed-in customer's saved currency is the default.
        $customer = $this->demo();
        $customer->forceFill(['SelectedCurrency' => 'EUR'])->save();
        $this->signIn($customer);
        $this->getJson($this->api("stays/{$hotel->IDs}"))->assertJsonPath('data.price_from.currency', 'EUR');
    }

    public function test_vehicles_home_stays_and_experiences(): void
    {
        foreach (['car', 'taxi', 'ambulance'] as $type) {
            $list = $this->getJson($this->api("vehicles/{$type}"))->assertOk()->json('data');
            $this->assertNotEmpty($list);
            $this->assertSame($type, $list[0]['type']);
            $this->getJson($this->api("vehicles/{$type}/{$list[0]['id']}"))->assertOk()->assertJsonStructure(['data' => ['gallery', 'extras'], 'related']);
            $this->getJson($this->api("vehicles/{$type}/categories"))->assertOk()->assertJsonStructure(['data' => [['category', 'count']]]);
        }
        $this->getJson($this->api('vehicles/boat'))->assertNotFound();

        $suv = $this->getJson($this->api('vehicles/car?category=SUV'))->json('data');
        $this->assertSame(['SUV'], array_values(array_unique(array_column($suv, 'category'))));

        $this->getJson($this->api('taxi-trips?airport=Entebbe'))->assertOk()->assertJsonStructure(['data' => [['airport', 'destination', 'fare']]]);

        $house = $this->getJson($this->api('home-stays'))->assertOk()->json('data.0');
        $this->getJson($this->api("home-stays/{$house['id']}"))->assertOk()->assertJsonStructure(['data' => ['details', 'room_features']]);

        foreach (['tour-packages', 'tourism-sites', 'events', 'blog'] as $path) {
            $first = $this->getJson($this->api($path))->assertOk()->json('data.0');
            $this->assertNotNull($first, $path);
            $this->getJson($this->api("{$path}/{$first['id']}"))->assertOk();
        }
        $this->getJson($this->api('blog/1'))->assertJsonStructure(['data' => ['body', 'comments'], 'related']);
    }

    public function test_search_and_destinations(): void
    {
        $this->getJson($this->api('search?q=Jinja'))->assertOk()
            ->assertJsonStructure(['query', 'data' => ['stays', 'cars', 'taxis', 'ambulances', 'tour_packages', 'tourism_sites', 'events']])
            ->assertJsonPath('data.stays.0.city', 'Jinja');

        $this->getJson($this->api('regions'))->assertOk()->assertJsonStructure(['data' => [['country', 'regions' => [['region', 'cities']]]]]);
        $this->getJson($this->api('cities?region=Central'))->assertOk()->assertJsonPath('data.0.region', 'Central');
        $this->getJson($this->api('cities/Jinja'))->assertOk()->assertJsonStructure(['data' => ['stays', 'cars', 'taxis', 'ambulances']]);
    }
}

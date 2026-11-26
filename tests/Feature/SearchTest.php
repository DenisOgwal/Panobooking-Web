<?php

namespace Tests\Feature;

use App\Livewire\GeneralSearch;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** Seed once when the test database is migrated; each test then runs in a rolled-back transaction. */
    protected $seed = true;


    private function search(string $term)
    {
        return Livewire::withQueryParams(['salessearch' => $term])->test(GeneralSearch::class);
    }

    public function test_unapproved_hotels_are_hidden_even_when_city_matches(): void
    {
        // Before the fix, ->where('Approved')->...->orWhere('City', ...) let this through.
        DB::table('pickuplocations')->insert(['Place' => 'Hidden Jinja Lodge', 'City' => 'Jinja', 'Approved' => 'Pending']);

        $this->search('Jinja')->assertViewHas('hotelsearched', function ($hotels) {
            $names = $hotels->pluck('Place');

            return $names->contains('Nile Breeze Hotel') && ! $names->contains('Hidden Jinja Lodge');
        });
    }

    public function test_unapproved_cars_are_hidden(): void
    {
        DB::table('carrentals')->insert(['TaxiNo' => 'UXX 999X', 'City' => 'Jinja', 'CurrentLocation' => 'Jinja', 'Approved' => 'Pending']);

        $this->search('Jinja')->assertViewHas('carsearched', fn ($cars) => ! $cars->pluck('TaxiNo')->contains('UXX 999X')
            && $cars->pluck('TaxiNo')->contains('UBD 404D'));
    }

    public function test_finished_events_and_past_tours_are_hidden(): void
    {
        DB::table('events')->insert(['EventName' => 'Old Jinja Fair', 'City' => 'Jinja', 'EndDate' => now()->subDay()->toDateString()]);
        DB::table('tourpackage')->insert(['PackageName' => 'Past Jinja Tour', 'City' => 'Jinja', 'TravelDate' => now()->subDay()->toDateString()]);

        $this->search('Jinja')
            ->assertViewHas('eventss', fn ($events) => $events->pluck('EventName')->all() === ['Nile Rafting Challenge'])
            ->assertViewHas('tourpackagesearched', fn ($tours) => $tours->pluck('PackageName')->all() === ['3-Day Jinja Adventure']);
    }

    public function test_airport_taxis_show_for_served_destinations_only(): void
    {
        // Jinja is a taxi-trip destination in the seed data; Gulu is not.
        $this->search('Jinja')->assertViewHas('taxisearched', fn ($taxis) => $taxis->count() === 3);
        $this->search('Gulu')->assertViewHas('taxisearched', fn ($taxis) => $taxis->isEmpty());
    }

    public function test_search_treats_like_wildcards_literally(): void
    {
        $this->assertSame(0, HotelsModel::approved()->search('%', HotelsModel::SEARCH_COLUMNS)->count());
        $this->assertSame(8, HotelsModel::approved()->search('', HotelsModel::SEARCH_COLUMNS)->count());
    }

    public function test_search_scope_groups_its_or_conditions(): void
    {
        $sql = RentalCarModel::approved()->search('x', ['City', 'TaxiNo'])->toSql();

        $this->assertStringContainsString('where `carrentals`.`Approved` = ? and (`carrentals`.`City` like ? or `carrentals`.`TaxiNo` like ?)', $sql);
    }

    public function test_taxi_destination_helper(): void
    {
        $this->assertTrue(TaxiModel::servesDestination('jinja'));
        $this->assertFalse(TaxiModel::servesDestination('Gulu'));
        $this->assertFalse(TaxiModel::servesDestination(''));
    }
}

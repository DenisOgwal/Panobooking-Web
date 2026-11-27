<?php

namespace App\Http\Controllers\Api\V1;

use App\Bookings\Types\CarBooking;
use App\Bookings\Types\TaxiBooking;
use App\Http\Resources\V1\BlogPostResource;
use App\Http\Resources\V1\ExperienceResource;
use App\Http\Resources\V1\Present;
use App\Http\Resources\V1\RoomResource;
use App\Http\Resources\V1\StayResource;
use App\Http\Resources\V1\TourPackageResource;
use App\Http\Resources\V1\VehicleResource;
use App\Listings\AmbulanceListing;
use App\Listings\CarListing;
use App\Listings\PropertyListing;
use App\Models\AmbulanceModel;
use App\Models\DestinationsModel;
use App\Models\events;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use App\Models\TaxiTripModel;
use App\Models\TourismSite;
use App\Models\TourPackages;
use App\Support\Currency;
use App\Support\Facets;
use App\Support\HomeContent;
use App\Support\ListingSort;
use App\Support\Vehicles;
use Illuminate\Http\Request;

/** App start-up data, the home screen, global search and destinations. */
class CatalogueController extends ApiController
{
    /** GET /meta: everything the app needs for pickers and filters. */
    public function meta()
    {
        $currency = app(Currency::class);

        return response()->json(['data' => [
            'currencies' => collect($currency->available())->map(fn ($code) => ['code' => $code, 'ugx_per_unit' => $currency->rate($code)])->values(),
            'base_currency' => Currency::BASE,
            'sort_options' => collect(ListingSort::OPTIONS)->map(fn ($label, $key) => ['key' => $key, 'label' => $label])->values(),
            'property_types' => PropertyListing::TYPES,
            'stay_cities' => Facets::hotelCities()->map(fn ($n, $city) => ['city' => $city, 'stays' => $n])->values(),
            'home_stay_cities' => Facets::guestHouseCities()->map(fn ($n, $city) => ['city' => $city, 'home_stays' => $n])->values(),
            'vehicle_categories' => collect(array_keys(Vehicles::TYPES))->mapWithKeys(fn ($t) => [$t => Vehicles::categories($t)
                ->map(fn ($n, $c) => ['category' => $c, 'count' => $n])->values()]),
            'taxi_airports' => TaxiTripModel::query()->whereNotNull('Froms')->distinct()->orderBy('Froms')->pluck('Froms')->values(),
            'taxi_trips' => TaxiBooking::TRIPS,
            'car_driving_areas' => CarBooking::AREAS,
            'listing_options' => [
                'property_types' => PropertyListing::TYPES,
                'car_categories' => CarListing::CATEGORIES,
                'car_commission' => CarListing::COMMISSION,
                'ambulance_categories' => AmbulanceListing::CATEGORIES,
            ],
            'pages' => [
                'faqs' => url('/Faqs'),
                'help' => url('/HelpCenter'),
                'terms' => url('/TermsAndConditions'),
                'privacy' => url('/privacypolicy'),
            ],
        ]]);
    }

    /** GET /home */
    public function home()
    {
        $c = HomeContent::get();

        return response()->json(['data' => [
            'offer' => $c['offer'] ? new RoomResource($c['offer']) : null,
            'trending' => StayResource::collection($c['trending']),
            'featured' => StayResource::collection($c['featured']),
            'destinations' => $c['destinations']->map(fn ($d) => $this->destination($d))->values(),
            'tourism_sites' => ExperienceResource::collection($c['sites']),
            'events' => ExperienceResource::collection($c['events']),
            'articles' => BlogPostResource::collection($c['articles']),
            'testimonials' => $c['testimonials']->map(fn ($t) => [
                'name' => $t->ClientName, 'job' => $t->ClientJob, 'company' => $t->ClientCompany, 'text' => $t->FeedBack,
            ])->values(),
            'stats' => $c['stats'],
        ]]);
    }

    /** GET /search?q= : grouped results across everything, like the website's search page. */
    public function search(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:120']);
        $term = $this->term($request);
        $limit = min(24, $this->perPage($request));

        return response()->json(['query' => $term, 'data' => [
            'stays' => StayResource::collection(HotelsModel::approved()->search($term, HotelsModel::SEARCH_COLUMNS)
                ->orderByRaw('FaturedRank IS NULL')->orderBy('FaturedRank')->limit($limit)->get()),
            'cars' => VehicleResource::collection(RentalCarModel::approved()->search($term, RentalCarModel::SEARCH_COLUMNS)->latest('updated_at')->limit($limit)->get()),
            'taxis' => VehicleResource::collection(TaxiModel::when(! TaxiModel::servesDestination($term), fn ($q) => $q->search($term, TaxiModel::SEARCH_COLUMNS))
                ->latest('updated_at')->limit($limit)->get()),
            'ambulances' => VehicleResource::collection(AmbulanceModel::approved()->search($term, AmbulanceModel::SEARCH_COLUMNS)->latest('updated_at')->limit($limit)->get()),
            'tour_packages' => TourPackageResource::collection(TourPackages::upcoming()->search($term, TourPackages::SEARCH_COLUMNS)->orderBy('TravelDate')->limit($limit)->get()),
            'tourism_sites' => ExperienceResource::collection(TourismSite::search($term, TourismSite::SEARCH_COLUMNS)->orderBy('SiteName')->limit($limit)->get()),
            'events' => ExperienceResource::collection(events::upcoming()->search($term, events::SEARCH_COLUMNS)->orderBy('StartDate')->limit($limit)->get()),
        ]]);
    }

    /** GET /regions: regions grouped by country, with their number of cities. */
    public function regions()
    {
        $regions = DestinationsModel::query()->whereNotNull('Region')
            ->selectRaw('Country, Region, COUNT(*) as cities')->groupBy('Country', 'Region')
            ->orderBy('Country')->orderBy('Region')->get();

        return response()->json(['data' => $regions->groupBy('Country')->map(fn ($rows, $country) => [
            'country' => $country,
            'regions' => $rows->map(fn ($r) => ['region' => $r->Region, 'cities' => (int) $r->cities])->values(),
        ])->values()]);
    }

    /** GET /cities?region=&country= */
    public function cities(Request $request)
    {
        $region = $this->term($request, 'region');
        $country = $this->term($request, 'country');

        $cities = DestinationsModel::query()
            ->when($region !== '', fn ($q) => $q->where('Region', $region))
            ->when($country !== '', fn ($q) => $q->where('Country', $country))
            ->orderBy('City')->get();
        $stays = Facets::hotelCities();

        return response()->json(['data' => $cities->map(fn ($d) => $this->destination($d, (int) ($stays[$d->City] ?? 0)))->values()]);
    }

    /** GET /cities/{city}: what's on offer in a city (stays, cars, taxis, ambulances). */
    public function city(Request $request, string $city)
    {
        // Same selection as the website's /Properties?cityslug= page.
        $country = $this->term($request, 'country');

        return response()->json(['city' => $city, 'data' => [
            'stays' => StayResource::collection(HotelsModel::approved()->where('City', $city)
                ->when($country !== '', fn ($q) => $q->where('Country', $country))
                ->orderByRaw('TrendRank IS NULL')->orderBy('TrendRank')->limit(24)->get()),
            'cars' => VehicleResource::collection(RentalCarModel::approved()->where('City', $city)->limit(12)->get()),
            'taxis' => VehicleResource::collection(TaxiModel::forDestination($city)->limit(12)->get()),
            'ambulances' => VehicleResource::collection(AmbulanceModel::approved()->where('City', $city)->limit(12)->get()),
        ]]);
    }

    private function destination($d, ?int $stays = null): array
    {
        return [
            'city' => $d->City,
            'region' => $d->Region,
            'country' => $d->Country,
            'stays' => $stays ?? (int) ($d->stays ?? 0),
            'photo' => \App\Support\Media::city((string) $d->City),
        ];
    }
}

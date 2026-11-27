<?php

namespace App\Http\Controllers\Api\V1;

use App\Bookings\Types\TaxiBooking;
use App\Http\Resources\V1\Present;
use App\Http\Resources\V1\VehicleResource;
use App\Models\TaxiTripModel;
use App\Support\ListingSort;
use App\Support\Vehicles;
use Illuminate\Http\Request;

/** Rental cars, airport taxis and ambulances ({type} = car | taxi | ambulance). */
class VehiclesController extends ApiController
{
    /** GET /vehicles/{type}?category=&location=&destination=&q=&sort= */
    public function index(Request $request, string $type)
    {
        $request->validate(['category' => 'nullable|string|max:120', 'location' => 'nullable|string|max:120',
            'destination' => 'nullable|string|max:200', 'q' => 'nullable|string|max:120', 'sort' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1']);

        $c = Vehicles::config($type);
        $category = $this->term($request, 'category');
        $location = $this->term($request, 'location');
        $destination = $this->term($request, 'destination');

        $query = Vehicles::query($type)
            ->when($category !== '', fn ($q) => $q->where($c['category'], $category))
            ->when($location !== '', fn ($q) => $q->search($location, ['CurrentLocation', 'City']))
            ->when($type === 'taxi' && $destination !== '', fn ($q) => $q->forDestination($destination))
            ->search($this->term($request), $c['model']::SEARCH_COLUMNS);

        // Ambulances have no customer-facing price, so price sorts fall back to recommended.
        $sort = $type === 'ambulance' && str_starts_with((string) $request->query('sort'), 'price') ? 'recommended' : $request->query('sort');

        return VehicleResource::collection(
            ListingSort::apply($query, $sort, 'Price', 'CarSpec', 'Ratings')->paginate($this->perPage($request))
        );
    }

    /** GET /vehicles/{type}/categories */
    public function categories(string $type)
    {
        return response()->json(['data' => Vehicles::categories($type)->map(fn ($n, $category) => [
            'category' => $category, 'count' => $n, 'photo' => \App\Support\Media::category((string) $category),
        ])->values()]);
    }

    /** GET /vehicles/{type}/{id} */
    public function show(string $type, int $id)
    {
        $c = Vehicles::config($type);
        $vehicle = Vehicles::query($type)->findOrFail($id);
        $related = Vehicles::query($type)->where($c['category'], $vehicle->{$c['category']})->where('IDs', '!=', $vehicle->IDs)->limit(8)->get();

        return response()->json([
            'data' => (new VehicleResource($vehicle))->detailed(),
            'related' => VehicleResource::collection($related),
        ]);
    }

    /** GET /taxi-trips?airport=Entebbe: destinations and fixed fares for airport transfers. */
    public function taxiTrips(Request $request)
    {
        $request->validate(['airport' => 'nullable|string|max:120']);
        $airport = $this->term($request, 'airport');

        $trips = TaxiTripModel::query()
            ->when($airport !== '', fn ($q) => $q->where('Froms', 'like', addcslashes($airport, '%_\\') . '%'))
            ->orderBy('Froms')->orderBy('Tos')->get();

        return response()->json(['data' => $trips->map(fn ($t) => [
            'id' => $t->id, 'airport' => $t->Froms, 'destination' => $t->Tos, 'fare' => Present::price($t->Price),
        ])->values(), 'trips' => TaxiBooking::TRIPS]);
    }
}

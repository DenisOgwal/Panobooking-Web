<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\HomeStayResource;
use App\Http\Resources\V1\RoomResource;
use App\Http\Resources\V1\StayDetailResource;
use App\Http\Resources\V1\StayResource;
use App\Models\HomeGuestModel;
use App\Models\HotelsModel;
use App\Models\RoomsModel;
use App\Support\ListingSort;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/** Hotels and other stays, their rooms, and home stays. Only approved stays are public. */
class StaysController extends ApiController
{
    /** GET /stays?city=&type=&q=&sort= */
    public function index(Request $request)
    {
        $request->validate(['city' => 'nullable|string|max:120', 'type' => 'nullable|string|max:60', 'q' => 'nullable|string|max:120',
            'sort' => 'nullable|string', 'per_page' => 'nullable|integer|min:1']);

        $city = $this->term($request, 'city');
        $type = $this->term($request, 'type');
        $types = array_values(array_unique(array_filter([$type, Str::singular($type)]))); // "Apartments" works too

        $query = HotelsModel::approved()
            ->when($city !== '', fn ($q) => $q->where('City', $city))
            ->when($types, fn ($q) => $q->whereIn('PlaceType', $types))
            ->search($this->term($request), ['City', 'Place', 'SearchTerms']);

        return StayResource::collection(
            ListingSort::apply($query, $request->query('sort'), 'Prices', 'Place', 'Rating', 'TrendRank')->paginate($this->perPage($request))
        );
    }

    /** GET /stays/{id} */
    public function show(int $id)
    {
        $stay = HotelsModel::approved()->with(['rooms' => fn ($q) => $q->with('roomdetails')->orderBy('Price')])->findOrFail($id);

        $related = HotelsModel::approved()->where('IDs', '!=', $stay->IDs)
            ->where(fn ($q) => $q->where('PlaceType', $stay->PlaceType)->orWhere('City', $stay->City))
            ->orderBy('TrendRank')->limit(8)->get();

        return response()->json([
            'data' => new StayDetailResource($stay),
            'related' => StayResource::collection($related),
        ]);
    }

    /** GET /rooms/{id} */
    public function room(int $id)
    {
        $room = RoomsModel::with(['roomdetails', 'hotel'])->findOrFail($id);
        abort_if(! $room->hotel || $room->hotel->Approved !== 'Approved', 404);

        return new RoomResource($room);
    }

    /** GET /home-stays?city=&sort= */
    public function homeStays(Request $request)
    {
        $request->validate(['city' => 'nullable|string|max:120', 'sort' => 'nullable|string', 'per_page' => 'nullable|integer|min:1']);
        $city = $this->term($request, 'city');

        $query = HomeGuestModel::query()->when($city !== '', fn ($q) => $q->where('City', $city));

        return HomeStayResource::collection(
            ListingSort::apply($query, $request->query('sort'), 'Prices', 'GuestHouseName')->paginate($this->perPage($request))
        );
    }

    /** GET /home-stays/{id} */
    public function homeStay(int $id)
    {
        $house = HomeGuestModel::with('roomdetails')->findOrFail($id);
        $related = HomeGuestModel::where('IDs', '!=', $house->IDs)->where('City', $house->City)->limit(8)->get();

        return response()->json([
            'data' => (new HomeStayResource($house))->detailed(),
            'related' => HomeStayResource::collection($related),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\BlogPostResource;
use App\Http\Resources\V1\ExperienceResource;
use App\Http\Resources\V1\TourPackageResource;
use App\Models\BlogModel;
use App\Models\events;
use App\Models\TourismSite;
use App\Models\TourPackages;
use App\Support\ListingSort;
use Illuminate\Http\Request;

/** Tour packages, tourism sites, events and the blog. */
class ExperiencesController extends ApiController
{
    /** GET /tour-packages?sort=: upcoming packages, soonest first by default. */
    public function tours(Request $request)
    {
        $query = TourPackages::upcoming();
        $sort = $request->query('sort');
        $query = in_array($sort, ['price_asc', 'price_desc', 'name'], true)
            ? ListingSort::apply($query, $sort, 'PackageFees2', 'PackageName', 'TripDays')
            : $query->orderBy('TravelDate');

        return TourPackageResource::collection($query->paginate($this->perPage($request)));
    }

    /** GET /tour-packages/{id} */
    public function tour(int $id)
    {
        $package = TourPackages::findOrFail($id);
        $more = TourPackages::upcoming()->where('IDs', '!=', $package->IDs)->orderBy('TravelDate')->limit(8)->get();

        return response()->json([
            'data' => (new TourPackageResource($package))->detailed(),
            'related' => TourPackageResource::collection($more),
        ]);
    }

    /** GET /tourism-sites?city= */
    public function sites(Request $request)
    {
        $city = $this->term($request, 'city');

        return ExperienceResource::collection(
            TourismSite::query()->when($city !== '', fn ($q) => $q->where('City', $city))
                ->search($this->term($request), TourismSite::SEARCH_COLUMNS)
                ->orderBy('SiteName')->paginate($this->perPage($request))
        );
    }

    /** GET /tourism-sites/{id} */
    public function site(int $id)
    {
        return (new ExperienceResource(TourismSite::findOrFail($id)))->detailed();
    }

    /** GET /events: upcoming events, soonest first. */
    public function events(Request $request)
    {
        return ExperienceResource::collection(
            events::upcoming()->search($this->term($request), events::SEARCH_COLUMNS)->orderBy('StartDate')->paginate($this->perPage($request))
        );
    }

    /** GET /events/{id} */
    public function event(int $id)
    {
        return (new ExperienceResource(events::findOrFail($id)))->detailed();
    }

    /** GET /blog */
    public function posts(Request $request)
    {
        return BlogPostResource::collection(
            BlogModel::withCount('approvedComments')->orderByDesc('Date')->orderByDesc('id')->paginate(min(50, (int) $request->query('per_page', 9)))
        );
    }

    /** GET /blog/{id}: the article with its approved comments. */
    public function post(int $id)
    {
        $post = BlogModel::withCount('approvedComments')->findOrFail($id);
        $more = BlogModel::where('id', '!=', $post->id)->orderByDesc('Date')->limit(3)->get();

        return response()->json([
            'data' => (new BlogPostResource($post))->detailed(),
            'related' => BlogPostResource::collection($more),
        ]);
    }
}

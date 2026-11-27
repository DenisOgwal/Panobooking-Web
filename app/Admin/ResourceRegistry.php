<?php

namespace App\Admin;

use App\Admin\Resources;
use Illuminate\Support\Facades\Gate;

/** Every table managed in the admin backend, in sidebar order. */
final class ResourceRegistry
{
    /** Sidebar groups, in order. */
    public const GROUPS = [
        'Stays' => 'bi-buildings',
        'Vehicles' => 'bi-car-front',
        'Experiences & content' => 'bi-compass',
        'Bookings & customers' => 'bi-receipt',
        'Settings' => 'bi-gear',
    ];

    /** @return list<class-string<Resource>> */
    public static function classes(): array
    {
        return [
            Resources\PropertyResource::class,
            Resources\RoomResource::class,
            Resources\GuestHouseResource::class,
            Resources\HomeStayLocationResource::class,
            Resources\DestinationResource::class,

            Resources\RentalCarResource::class,
            Resources\TaxiResource::class,
            Resources\TaxiTripResource::class,
            Resources\AmbulanceResource::class,

            Resources\TourPackageResource::class,
            Resources\TourismSiteResource::class,
            Resources\EventResource::class,
            Resources\BlogResource::class,
            Resources\BlogCommentResource::class,
            Resources\FeedbackResource::class,

            Resources\CustomerResource::class,
            Resources\MessageResource::class,

            Resources\CurrencyResource::class,
            Resources\AdminUserResource::class,
        ];
    }

    /** @return array<string, class-string<Resource>> slug => class */
    public static function all(): array
    {
        $map = [];
        foreach (self::classes() as $class) {
            $map[$class::slug()] = $class;
        }

        return $map;
    }

    /** @return class-string<Resource>|null */
    public static function find(string $slug): ?string
    {
        return self::all()[$slug] ?? null;
    }

    /** Resource class for a slug the current user may open; 404/403 otherwise. */
    public static function resolve(string $slug): string
    {
        $class = self::find($slug);
        abort_unless($class, 404);
        abort_if($class::adminOnly() && Gate::denies('admin.manage'), 403);

        return $class;
    }

    /** Sidebar entries visible to the current user: group => list of resource classes. */
    public static function navigation(): array
    {
        $nav = array_fill_keys(array_keys(self::GROUPS), []);
        foreach (self::classes() as $class) {
            if ($class::adminOnly() && Gate::denies('admin.manage')) {
                continue;
            }
            $nav[$class::group()][] = $class;
        }

        return array_filter($nav);
    }
}

<?php

namespace App\Admin;

use App\Models;
use Illuminate\Support\Facades\DB;

/**
 * The amenity, policy and surroundings tables of a stay (see Models\Concerns\HasPropertyDetails).
 * Every table joins on its `PropertyName` column, which holds the hotel's `Place` or the guest house's `GuestHouseName`.
 *
 * kind: 'flags' = one row of Yes/No columns, 'form' = one row of text columns, 'rows' = many rows.
 */
final class PropertyDetailSections
{
    public const TABS = [
        'overview' => 'Overview',
        'facilities' => 'Facilities',
        'dining' => 'Food & dining',
        'policies' => 'Policies',
        'surroundings' => 'Surroundings',
    ];

    public static function all(): array
    {
        $amenity = ['Amenity' => 'Amenity'];
        $distance = ['Place' => 'Place', 'Distance' => 'Distance'];

        return [
            'propertydescription' => ['Description', Models\PropertyDescriptionModel::class, 'rows', ['Description' => 'Paragraph'], 'overview'],
            'reasonstostayatproperty' => ['Reasons to stay', Models\ReasonsForHotelModel::class, 'rows', ['Reason' => 'Reason'], 'overview'],
            'popularamenities' => ['Popular amenities', Models\PopularAmenitiesModel::class, 'flags', [], 'overview'],
            'roomswith' => ['Room views', Models\RoomsWithModel::class, 'flags', [], 'overview'],

            'bathroom' => ['Bathroom', Models\BathroomModel::class, 'rows', $amenity, 'facilities'],
            'bedroom' => ['Bedroom', Models\BedroomModel::class, 'rows', $amenity, 'facilities'],
            'view' => ['Views', Models\Viewsmodel::class, 'rows', $amenity, 'facilities'],
            'outdoor' => ['Outdoors', Models\Outdoormodel::class, 'rows', $amenity, 'facilities'],
            'kitchen' => ['Kitchen', Models\KitchenModel::class, 'rows', $amenity, 'facilities'],
            'mediaandtechnology' => ['Media & technology', Models\MediaandTechModel::class, 'rows', $amenity, 'facilities'],
            'parking' => ['Parking', Models\ParkingModel::class, 'rows', $amenity, 'facilities'],
            'transportation' => ['Transportation', Models\TransportationModel::class, 'rows', $amenity, 'facilities'],
            'cleaning' => ['Cleaning services', Models\CleaningServicesModel::class, 'rows', $amenity, 'facilities'],
            'businessfacilities' => ['Business facilities', Models\BusinessFacilityModel::class, 'rows', $amenity, 'facilities'],
            'security' => ['Safety & security', Models\SafetyandSecurityModel::class, 'rows', $amenity, 'facilities'],
            'spa' => ['Spa & wellness', Models\SpaModel::class, 'rows', $amenity, 'facilities'],
            'accessibility' => ['Accessibility', Models\AccessibilityModel::class, 'rows', $amenity, 'facilities'],

            'foodanddrink' => ['Food & drink', Models\FoodandDrinkModel::class, 'rows', $amenity, 'dining'],
            'restaurantsonsite' => ['Restaurants on site', Models\OnsiteRestaurantModel::class, 'rows',
                ['RestaurantName' => 'Restaurant', 'Food' => 'Food', 'OpenFor' => 'Open for'], 'dining'],

            'houserules' => ['House rules', Models\HouseRulesModel::class, 'form', ['CheckIn' => 'Check-in', 'CheckOut' => 'Check-out',
                'Pets' => 'Pets', 'AgeRestriction' => 'Age restriction', 'CardAcceptability' => 'Accepted payments',
                'PaymentCancelation' => 'Cancellation / prepayment'], 'policies'],
            'childrenpolicies' => ['Children policies', Models\ChildrenPolicyModel::class, 'rows', ['Policy' => 'Policy'], 'policies'],
            'childandextrabed' => ['Cribs & extra beds', Models\CribandExtrabedModel::class, 'rows',
                ['YearsRange' => 'Ages', 'ExtraCondition' => 'Condition / charge'], 'policies'],

            'nearbyplaces' => ['Nearby places', Models\NearbyPlacesModel::class, 'rows', $distance, 'surroundings'],
            'topattractions' => ['Top attractions', Models\TopAttractionsModel::class, 'rows', $distance, 'surroundings'],
            'nearbyrestaurants' => ['Restaurants and cafes nearby', Models\NearbyRestaurants::class, 'rows', $distance, 'surroundings'],
            'nearbymarkets' => ['Markets nearby', Models\NearbyMarkets::class, 'rows', $distance, 'surroundings'],
            'nearbysupermarkets' => ['Supermarkets nearby', Models\NearbySupermarkets::class, 'rows', $distance, 'surroundings'],
            'naturalbeauty' => ['Natural beauty', Models\NaturalBeautyModel::class, 'rows', $distance, 'surroundings'],
            'closestairport' => ['Closest airports', Models\ClosestAirportModel::class, 'rows', ['Airport' => 'Airport', 'Distance' => 'Distance'], 'surroundings'],
            'publictransport' => ['Public transport', Models\PublicTransportModel::class, 'rows',
                ['TransportName' => 'Name', 'TransportType' => 'Type', 'Distance' => 'Distance'], 'surroundings'],
        ];
    }

    /** @return array{label: string, model: class-string, kind: string, fields: array, tab: string} */
    public static function get(string $key): array
    {
        [$label, $model, $kind, $fields, $tab] = self::all()[$key];

        return compact('label', 'model', 'kind', 'fields', 'tab');
    }

    /** Point every detail row of a property at its new name. */
    public static function rename(string $from, string $to): void
    {
        if ($from === $to || $from === '') {
            return;
        }
        foreach (array_keys(self::all()) as $table) {
            DB::table($table)->where('PropertyName', $from)->update(['PropertyName' => $to]);
        }
    }

    public static function deleteFor(string $name): void
    {
        if ($name === '') {
            return;
        }
        foreach (array_keys(self::all()) as $table) {
            DB::table($table)->where('PropertyName', $name)->delete();
        }
    }
}

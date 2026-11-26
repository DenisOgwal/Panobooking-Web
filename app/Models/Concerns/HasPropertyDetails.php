<?php

namespace App\Models\Concerns;

use App\Models\AccessibilityModel;
use App\Models\BathroomModel;
use App\Models\BedroomModel;
use App\Models\BusinessFacilityModel;
use App\Models\ChildrenPolicyModel;
use App\Models\CleaningServicesModel;
use App\Models\ClosestAirportModel;
use App\Models\CribandExtrabedModel;
use App\Models\FoodandDrinkModel;
use App\Models\HouseRulesModel;
use App\Models\KitchenModel;
use App\Models\MediaandTechModel;
use App\Models\NaturalBeautyModel;
use App\Models\NearbyMarkets;
use App\Models\NearbyPlacesModel;
use App\Models\NearbyRestaurants;
use App\Models\NearbySupermarkets;
use App\Models\OnsiteRestaurantModel;
use App\Models\Outdoormodel;
use App\Models\ParkingModel;
use App\Models\PopularAmenitiesModel;
use App\Models\PropertyDescriptionModel;
use App\Models\PublicTransportModel;
use App\Models\ReasonsForHotelModel;
use App\Models\RoomsWithModel;
use App\Models\SafetyandSecurityModel;
use App\Models\SpaModel;
use App\Models\TopAttractionsModel;
use App\Models\TransportationModel;
use App\Models\Viewsmodel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Amenity, policy and surroundings relations shared by hotels and guest houses.
 *
 * The detail tables join on their `PropertyName` column; the model using this trait
 * says which of its own columns holds the property name via propertyNameColumn().
 */
trait HasPropertyDetails
{
    /** Every relation, for eager loading on detail pages. */
    public const PROPERTY_DETAIL_RELATIONS = [
        'roomwith', 'popularamenities', 'propertydescription', 'bathroomdetail', 'bedroomdetail', 'viewdetail',
        'outdoordetail', 'mediaandtechdetail', 'fooddetail', 'parkingdetail', 'transportationdetail', 'cleaningdetail',
        'businessfacilitydetail', 'safetyandsecuritydetail', 'spadetail', 'kitchendetail', 'accessibilitydetail',
        'houserulesdetail', 'childrenpolicydetail', 'childrenextrabeddetail', 'nearbyplacesdetail', 'topattractionsdetail',
        'nearbyrestaurantsdetail', 'closestairportdetail', 'publictransportdetail', 'naturalbeautydetail',
        'nearbymarketsdetail', 'nearbysupermarketsdetail', 'reasonsforstaydetail', 'restaurantsonsitedetail',
    ];

    abstract protected function propertyNameColumn(): string;

    protected function detailOne(string $model): HasOne
    {
        return $this->hasOne($model, 'PropertyName', $this->propertyNameColumn());
    }

    protected function detailMany(string $model): HasMany
    {
        return $this->hasMany($model, 'PropertyName', $this->propertyNameColumn());
    }

    public function roomwith(): HasOne { return $this->detailOne(RoomsWithModel::class); }
    public function popularamenities(): HasOne { return $this->detailOne(PopularAmenitiesModel::class); }
    public function houserulesdetail(): HasOne { return $this->detailOne(HouseRulesModel::class); }

    public function propertydescription(): HasMany { return $this->detailMany(PropertyDescriptionModel::class); }
    public function bathroomdetail(): HasMany { return $this->detailMany(BathroomModel::class); }
    public function bedroomdetail(): HasMany { return $this->detailMany(BedroomModel::class); }
    public function viewdetail(): HasMany { return $this->detailMany(Viewsmodel::class); }
    public function outdoordetail(): HasMany { return $this->detailMany(Outdoormodel::class); }
    public function mediaandtechdetail(): HasMany { return $this->detailMany(MediaandTechModel::class); }
    public function fooddetail(): HasMany { return $this->detailMany(FoodandDrinkModel::class); }
    public function parkingdetail(): HasMany { return $this->detailMany(ParkingModel::class); }
    public function transportationdetail(): HasMany { return $this->detailMany(TransportationModel::class); }
    public function cleaningdetail(): HasMany { return $this->detailMany(CleaningServicesModel::class); }
    public function businessfacilitydetail(): HasMany { return $this->detailMany(BusinessFacilityModel::class); }
    public function safetyandsecuritydetail(): HasMany { return $this->detailMany(SafetyandSecurityModel::class); }
    public function spadetail(): HasMany { return $this->detailMany(SpaModel::class); }
    public function kitchendetail(): HasMany { return $this->detailMany(KitchenModel::class); }
    public function accessibilitydetail(): HasMany { return $this->detailMany(AccessibilityModel::class); }
    public function childrenpolicydetail(): HasMany { return $this->detailMany(ChildrenPolicyModel::class); }
    public function childrenextrabeddetail(): HasMany { return $this->detailMany(CribandExtrabedModel::class); }
    public function nearbyplacesdetail(): HasMany { return $this->detailMany(NearbyPlacesModel::class); }
    public function topattractionsdetail(): HasMany { return $this->detailMany(TopAttractionsModel::class); }
    public function nearbyrestaurantsdetail(): HasMany { return $this->detailMany(NearbyRestaurants::class); }
    public function closestairportdetail(): HasMany { return $this->detailMany(ClosestAirportModel::class); }
    public function publictransportdetail(): HasMany { return $this->detailMany(PublicTransportModel::class); }
    public function naturalbeautydetail(): HasMany { return $this->detailMany(NaturalBeautyModel::class); }
    public function nearbymarketsdetail(): HasMany { return $this->detailMany(NearbyMarkets::class); }
    public function nearbysupermarketsdetail(): HasMany { return $this->detailMany(NearbySupermarkets::class); }
    public function reasonsforstaydetail(): HasMany { return $this->detailMany(ReasonsForHotelModel::class); }
    public function restaurantsonsitedetail(): HasMany { return $this->detailMany(OnsiteRestaurantModel::class); }
}

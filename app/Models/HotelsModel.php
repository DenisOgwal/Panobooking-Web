<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelsModel extends Model
{
    use HasFactory;
    protected $table="pickuplocations";
    public function roomwith()
    {
        return $this->hasOne('App\Models\RoomsWithModel','PropertyName','Place');
    }
    public function popularamenities()
    {
        return $this->hasOne('App\Models\PopularAmenitiesModel','PropertyName','Place');
    }
    public function propertydescription()
    {
        return $this->hasMany('App\Models\PropertyDescriptionModel','PropertyName','Place');
    }
    public function bathroomdetail()
    {
        return $this->hasMany('App\Models\BathroomModel','PropertyName','Place');
    }
    public function bedroomdetail()
    {
        return $this->hasMany('App\Models\BedroomModel','PropertyName','Place');
    }
    public function viewdetail()
    {
        return $this->hasMany('App\Models\Viewsmodel','PropertyName','Place');
    }
    public function outdoordetail()
    {
        return $this->hasMany('App\Models\Outdoormodel','PropertyName','Place');
    }
    public function mediaandtechdetail()
    {
        return $this->hasMany('App\Models\MediaandTechModel','PropertyName','Place');
    }
    public function fooddetail()
    {
        return $this->hasMany('App\Models\FoodandDrinkModel','PropertyName','Place');
    }
    public function parkingdetail()
    {
        return $this->hasMany('App\Models\ParkingModel','PropertyName','Place');
    }
    public function transportationdetail()
    {
        return $this->hasMany('App\Models\TransportationModel','PropertyName','Place');
    }
    public function cleaningdetail()
    {
        return $this->hasMany('App\Models\CleaningServicesModel','PropertyName','Place');
    }
    public function businessfacilitydetail()
    {
        return $this->hasMany('App\Models\BusinessFacilityModel','PropertyName','Place');
    }
    public function safetyandsecuritydetail()
    {
        return $this->hasMany('App\Models\SafetyandSecurityModel','PropertyName','Place');
    }
    public function spadetail()
    {
        return $this->hasMany('App\Models\SpaModel','PropertyName','Place');
    }
    public function kitchendetail()
    {
        return $this->hasMany('App\Models\KitchenModel','PropertyName','Place');
    }
    public function accessibilitydetail()
    {
        return $this->hasMany('App\Models\AccessibilityModel','PropertyName','Place');
    }
    public function houserulesdetail()
    {
        return $this->hasOne('App\Models\HouseRulesModel','PropertyName','Place');
    }
    public function childrenpolicydetail()
    {
        return $this->hasMany('App\Models\ChildrenPolicyModel','PropertyName','Place');
    }
    public function childrenextrabeddetail()
    {
        return $this->hasMany('App\Models\CribandExtrabedModel','PropertyName','Place');
    }
    public function nearbyplacesdetail()
    {
        return $this->hasMany('App\Models\NearbyPlacesModel','PropertyName','Place');
    }
    public function topattractionsdetail()
    {
        return $this->hasMany('App\Models\TopAttractionsModel','PropertyName','Place');
    }
    public function nearbyrestaurantsdetail()
    {
        return $this->hasMany('App\Models\NearbyRestaurants','PropertyName','Place');
    }
    public function closestairportdetail()
    {
        return $this->hasMany('App\Models\ClosestAirportModel','PropertyName','Place');
    }
    public function publictransportdetail()
    {
        return $this->hasMany('App\Models\PublicTransportModel','PropertyName','Place');
    }
    public function naturalbeautydetail()
    {
        return $this->hasMany('App\Models\NaturalBeautyModel','PropertyName','Place');
    }
    public function nearbymarketsdetail()
    {
        return $this->hasMany('App\Models\NearbyMarkets','PropertyName','Place');
    }
    public function nearbysupermarketsdetail()
    {
        return $this->hasMany('App\Models\NearbySupermarkets','PropertyName','Place');
    }
    public function reasonsforstaydetail()
    {
        return $this->hasMany('App\Models\ReasonsForHotelModel','PropertyName','Place');
    }
    public function restaurantsonsitedetail()
    {
        return $this->hasMany('App\Models\OnsiteRestaurantModel','PropertyName','Place');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeGuestModel extends Model
{
    use HasFactory;
    protected $table="homeguesthouses";
    public function roomdetails()
    {
        return $this->hasOne('App\Models\RoomDetailsModel','RoomName','GuestHouseName');
    }
    public function roomwith()
    {
        return $this->hasOne('App\Models\RoomsWithModel','PropertyName','GuestHouseName');
    }
    public function popularamenities()
    {
        return $this->hasOne('App\Models\PopularAmenitiesModel','PropertyName','GuestHouseName');
    }
    public function propertydescription()
    {
        return $this->hasMany('App\Models\PropertyDescriptionModel','PropertyName','GuestHouseName');
    }
    public function bathroomdetail()
    {
        return $this->hasMany('App\Models\BathroomModel','PropertyName','GuestHouseName');
    }
    public function bedroomdetail()
    {
        return $this->hasMany('App\Models\BedroomModel','PropertyName','GuestHouseName');
    }
    public function viewdetail()
    {
        return $this->hasMany('App\Models\Viewsmodel','PropertyName','GuestHouseName');
    }
    public function outdoordetail()
    {
        return $this->hasMany('App\Models\Outdoormodel','PropertyName','GuestHouseName');
    }
    public function mediaandtechdetail()
    {
        return $this->hasMany('App\Models\MediaandTechModel','PropertyName','GuestHouseName');
    }
    public function fooddetail()
    {
        return $this->hasMany('App\Models\FoodandDrinkModel','PropertyName','GuestHouseName');
    }
    public function parkingdetail()
    {
        return $this->hasMany('App\Models\ParkingModel','PropertyName','GuestHouseName');
    }
    public function transportationdetail()
    {
        return $this->hasMany('App\Models\TransportationModel','PropertyName','GuestHouseName');
    }
    public function cleaningdetail()
    {
        return $this->hasMany('App\Models\CleaningServicesModel','PropertyName','GuestHouseName');
    }
    public function businessfacilitydetail()
    {
        return $this->hasMany('App\Models\BusinessFacilityModel','PropertyName','GuestHouseName');
    }
    public function safetyandsecuritydetail()
    {
        return $this->hasMany('App\Models\SafetyandSecurityModel','PropertyName','GuestHouseName');
    }
    public function spadetail()
    {
        return $this->hasMany('App\Models\SpaModel','PropertyName','GuestHouseName');
    }
    public function kitchendetail()
    {
        return $this->hasMany('App\Models\KitchenModel','PropertyName','GuestHouseName');
    }
    public function accessibilitydetail()
    {
        return $this->hasMany('App\Models\AccessibilityModel','PropertyName','GuestHouseName');
    }
    public function houserulesdetail()
    {
        return $this->hasOne('App\Models\HouseRulesModel','PropertyName','GuestHouseName');
    }
    public function childrenpolicydetail()
    {
        return $this->hasMany('App\Models\ChildrenPolicyModel','PropertyName','GuestHouseName');
    }
    public function childrenextrabeddetail()
    {
        return $this->hasMany('App\Models\CribandExtrabedModel','PropertyName','GuestHouseName');
    }
    public function nearbyplacesdetail()
    {
        return $this->hasMany('App\Models\NearbyPlacesModel','PropertyName','GuestHouseName');
    }
    public function topattractionsdetail()
    {
        return $this->hasMany('App\Models\TopAttractionsModel','PropertyName','GuestHouseName');
    }
    public function nearbyrestaurantsdetail()
    {
        return $this->hasMany('App\Models\NearbyRestaurants','PropertyName','GuestHouseName');
    }
    public function closestairportdetail()
    {
        return $this->hasMany('App\Models\ClosestAirportModel','PropertyName','GuestHouseName');
    }
    public function publictransportdetail()
    {
        return $this->hasMany('App\Models\PublicTransportModel','PropertyName','GuestHouseName');
    }
    public function naturalbeautydetail()
    {
        return $this->hasMany('App\Models\NaturalBeautyModel','PropertyName','GuestHouseName');
    }
    public function nearbymarketsdetail()
    {
        return $this->hasMany('App\Models\NearbyMarkets','PropertyName','GuestHouseName');
    }
    public function nearbysupermarketsdetail()
    {
        return $this->hasMany('App\Models\NearbySupermarkets','PropertyName','GuestHouseName');
    }
    public function reasonsforstaydetail()
    {
        return $this->hasMany('App\Models\ReasonsForHotelModel','PropertyName','GuestHouseName');
    }
    public function restaurantsonsitedetail()
    {
        return $this->hasMany('App\Models\OnsiteRestaurantModel','PropertyName','GuestHouseName');
    }
}

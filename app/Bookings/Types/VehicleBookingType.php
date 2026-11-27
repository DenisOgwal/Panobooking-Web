<?php

namespace App\Bookings\Types;

use App\Bookings\BookingType;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Model;

/** Rental cars, airport taxis and ambulances: only public vehicles (see App\Support\Vehicles). */
abstract class VehicleBookingType extends BookingType
{
    /** car | taxi | ambulance */
    abstract protected function vehicleType(): string;

    public function find(int|string|null $id): ?Model
    {
        return $id === null ? null : Vehicles::query($this->vehicleType())->find($id);
    }

    /** Cart columns every vehicle booking shares. */
    protected function vehicleCart(Model $vehicle): array
    {
        return ['Facility' => $vehicle->DriverName ?: $vehicle->Company, 'ProductName' => $vehicle->TaxiNo];
    }
}

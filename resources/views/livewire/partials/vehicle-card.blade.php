{{--
    Card for a rental car, airport taxi or ambulance.
    Expects: $vehicle (model), $type ('car'|'taxi'|'ambulance')
--}}
@php
    use App\Support\Media;
    use App\Support\Vehicles;
    $c = Vehicles::config($type);
    $isAmbulance = $type === 'ambulance';
@endphp
<x-listing-card
    :href="Vehicles::detailUrl($type, $vehicle)"
    :image="Media::image($type, $vehicle)"
    :title="$vehicle->CarSpec ?: $vehicle->TaxiNo"
    :location="$vehicle->CurrentLocation ?: $vehicle->City"
    :rating="$vehicle->Ratings"
    :price="$isAmbulance ? null : $vehicle->Price"
    :unit="$c['unit']"
    :badge="$vehicle->{$c['category']}"
    :cta="$isAmbulance ? 'Request' : 'View'">
    @if (! $isAmbulance)
        @if (filled($vehicle->Passengers))<span class="tag"><i class="bi bi-people"></i>{{ $vehicle->Passengers }}</span>@endif
        @if (filled($vehicle->Laggages))<span class="tag"><i class="bi bi-suitcase2"></i>{{ $vehicle->Laggages }}</span>@endif
        @if ($vehicle->Automatic === 'Yes')<span class="tag"><i class="bi bi-gear"></i>Automatic</span>@endif
    @else
        <span class="tag"><i class="bi bi-hash"></i>{{ $vehicle->TaxiNo }}</span>
        @if ($vehicle->Availability === 'Available')<span class="tag text-success"><i class="bi bi-check2-circle"></i>Available</span>@endif
    @endif
</x-listing-card>

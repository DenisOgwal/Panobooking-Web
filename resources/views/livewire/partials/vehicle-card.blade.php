{{--
    Card for a rental car, airport taxi or ambulance.
    Expects: $vehicle (model), $type ('car'|'taxi'|'ambulance')
--}}
@php
    use App\Support\Media;
    $routes = [
        'car' => ['CarDetails', 'carslug', 'per day'],
        'taxi' => ['TaxiDetails', 'taxislug', 'per trip'],
        'ambulance' => ['AmbulanceDetails', 'ambulanceslug', null],
    ];
    [$routeName, $param, $unit] = $routes[$type];
    $category = $type === 'ambulance' ? $vehicle->AmbulanceCategory : $vehicle->CarCategory;
@endphp
<x-listing-card
    :href="route($routeName, [$param => $vehicle->IDs])"
    :image="Media::image($type, $vehicle)"
    :title="$vehicle->CarSpec ?: $vehicle->TaxiNo"
    :location="$vehicle->CurrentLocation ?: $vehicle->City"
    :rating="$vehicle->Ratings"
    :price="$type === 'ambulance' ? null : $vehicle->Price"
    :unit="$unit"
    :badge="$category"
    :cta="$type === 'ambulance' ? 'Request' : 'View'">
    @if ($type !== 'ambulance')
        @if (filled($vehicle->Passengers))<span class="tag"><i class="bi bi-people"></i>{{ $vehicle->Passengers }}</span>@endif
        @if (filled($vehicle->Laggages))<span class="tag"><i class="bi bi-suitcase2"></i>{{ $vehicle->Laggages }}</span>@endif
        @if ($vehicle->Automatic === 'Yes')<span class="tag"><i class="bi bi-gear"></i>Automatic</span>@endif
    @else
        <span class="tag"><i class="bi bi-hash"></i>{{ $vehicle->TaxiNo }}</span>
        @if ($vehicle->Availability === 'Available')<span class="tag"><i class="bi bi-check2-circle"></i>Available</span>@endif
    @endif
</x-listing-card>

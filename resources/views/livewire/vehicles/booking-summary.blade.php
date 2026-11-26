{{-- Right-hand summary on vehicle booking pages. Expects $vehicle, $type, $c; optional $lines ([label, value]), $total (UGX|null). --}}
@php use App\Support\Media; @endphp
<div class="booking-box">
    <div class="d-flex gap-3 mb-3">
        <img class="rounded-3 flex-shrink-0 object-fit-cover" width="112" height="84" src="{{ Media::image($type, $vehicle) }}" data-fallback="{{ Media::placeholder() }}" alt="">
        <div>
            <div class="small text-muted">{{ $vehicle->{$c['category']} ?: $c['label'] }}</div>
            <h2 class="h5 mb-1">{{ $vehicle->CarSpec ?: $vehicle->TaxiNo }}</h2>
            <div class="small text-muted"><i class="bi bi-hash"></i>{{ $vehicle->TaxiNo }} · {{ $vehicle->DriverName ?: $vehicle->Company }}</div>
        </div>
    </div>
    @foreach ($lines ?? [] as [$label, $value])
        <div class="summary-line"><span>{{ $label }}</span><span>{{ $value }}</span></div>
    @endforeach
    @if (! empty($showTotal) || isset($total))
        <div class="total"><span>Total</span>@if ($total !== null)<x-price :amount="$total" size="lg" />@else<span class="text-muted">On request</span>@endif</div>
    @endif
    <p class="small text-muted mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Nothing to pay online. The provider confirms your request first.</p>
</div>

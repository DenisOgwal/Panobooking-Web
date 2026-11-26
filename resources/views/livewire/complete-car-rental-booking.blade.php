@php
    use App\Support\Vehicles;
    $vehicle = $this->vehicle;
    $currency = app(\App\Support\Currency::class);
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $hasUpcountry = $vehicle->PricePerDay && (float) $vehicle->PricePerDay !== (float) $vehicle->Price;
@endphp
<div>
    <x-page-hero title="Rent this car" :subtitle="($vehicle->CarSpec ?: $vehicle->TaxiNo) . ' from ' . ($vehicle->Company ?: $vehicle->DriverName)"
                 :crumbs="[['Car rental', '/cars'], [$vehicle->CarSpec ?: $vehicle->TaxiNo, Vehicles::detailUrl('car', $vehicle)], ['Book']]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <h2 class="h4 mb-3">Rental details</h2>
                        <form wire:submit="book" novalidate>
                            <div class="row g-3">
                                <div class="col-sm-6"><x-field label="Pick-up date" name="checkindate" type="date" wire:model.live="checkindate" :min="now()->toDateString()" icon="bi-calendar-event" /></div>
                                <div class="col-3 col-sm-3"><x-field label="Days" name="Duration" type="number" min="1" max="90" wire:model.live.debounce.300ms="Duration" /></div>
                                <div class="col-3 col-sm-3"><x-field label="Cars" name="Carsquantity" type="number" min="1" max="15" wire:model.live.debounce.300ms="Carsquantity" /></div>
                            </div>
                            @if ($hasUpcountry)
                                <fieldset class="mb-3">
                                    <legend class="form-label">Where will you drive?</legend>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach (['Kampala' => [$vehicle->Price, 'Within Kampala'], 'Upcountry' => [$vehicle->PricePerDay, 'Upcountry']] as $value => [$price, $label])
                                            <input type="radio" class="btn-check" id="drive-{{ $value }}" value="{{ $value }}" wire:model.live="driveloc">
                                            <label class="btn btn-outline-primary" for="drive-{{ $value }}">{{ $label }} · {{ $currency->format($price) }}/day</label>
                                        @endforeach
                                    </div>
                                </fieldset>
                            @endif
                            <x-field label="Pick-up point" name="pickuppoint" wire:model.blur="pickuppoint" icon="bi-geo-alt" placeholder="Hotel, address or airport" />
                            <x-field label="Notes (optional)" name="bookingnotes" textarea rows="2" wire:model.blur="bookingnotes" placeholder="Need a driver? Child seat? Tell the provider." />
                            @if ($loggedIn)
                                <button class="btn btn-accent btn-lg w-100" type="submit">
                                    <span wire:loading.remove wire:target="book">Request this car</span>
                                    <span wire:loading wire:target="book"><span class="spinner-border spinner-border-sm me-2"></span>Sending…</span>
                                </button>
                            @endif
                        </form>
                        @unless ($loggedIn)<div class="mt-3">@include('livewire.partials.signin-card')</div>@endunless
                    </div>
                </div>
                <aside class="col-lg-5">
                    @include('livewire.vehicles.booking-summary', [
                        'vehicle' => $vehicle,
                        'lines' => [
                            [$currency->format($this->rate) . ' × ' . max(0, (int) $Duration) . ' ' . \Illuminate\Support\Str::plural('day', (int) $Duration), $currency->format($this->rate * max(0, (int) $Duration))],
                            ['Cars', '× ' . max(0, (int) $Carsquantity)],
                            ['Driving', $driveloc === 'Upcountry' ? 'Upcountry' : 'Within Kampala'],
                        ],
                        'total' => $this->total,
                    ])
                </aside>
            </div>
        </div>
    </section>
</div>

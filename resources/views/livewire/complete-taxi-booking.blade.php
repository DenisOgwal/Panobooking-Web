@php
    use App\Support\Vehicles;
    $vehicle = $this->vehicle;
    $currency = app(\App\Support\Currency::class);
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $fare = $this->fare;
@endphp
<div>
    <x-page-hero title="Book an airport taxi" :subtitle="($vehicle->CarSpec ?: $vehicle->TaxiNo) . ' with ' . $vehicle->DriverName"
                 :crumbs="[['Airport taxi', '/taxis'], [$vehicle->CarSpec ?: $vehicle->TaxiNo, Vehicles::detailUrl('taxi', $vehicle)], ['Book']]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <h2 class="h4 mb-3">Your transfer</h2>
                        <form wire:submit="book" novalidate>
                            <fieldset class="mb-3">
                                <legend class="form-label">Trip</legend>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (['From Airport' => 'From the airport', 'To Airport' => 'To the airport'] as $value => $label)
                                        <input type="radio" class="btn-check" id="trip-{{ \Illuminate\Support\Str::slug($value) }}" value="{{ $value }}" wire:model.live="pickuppoint">
                                        <label class="btn btn-outline-primary" for="trip-{{ \Illuminate\Support\Str::slug($value) }}">{{ $label }}</label>
                                    @endforeach
                                </div>
                            </fieldset>
                            <div class="row g-3">
                                <div class="col-sm-5">
                                    <x-field label="Airport" name="airportfield" select wire:model.live="airportfield"><option value="Entebbe">Entebbe International</option></x-field>
                                </div>
                                <div class="col-sm-7">
                                    <x-field :label="$pickuppoint === 'From Airport' ? 'Drop-off' : 'Pick-up from'" name="Destination" select wire:model.live="Destination">
                                        @forelse ($this->destinations as $d)<option value="{{ $d }}">{{ $d }}</option>@empty<option value="">No routes available</option>@endforelse
                                    </x-field>
                                </div>
                                <div class="col-sm-5"><x-field label="Date" name="checkindate" type="date" wire:model.live="checkindate" :min="now()->toDateString()" icon="bi-calendar-event" /></div>
                                <div class="col-6 col-sm-4"><x-field label="Time" name="pickuptime" type="time" wire:model.blur="pickuptime" icon="bi-clock" /></div>
                                <div class="col-6 col-sm-3"><x-field label="People" name="NoOfPeople" type="number" min="1" max="14" wire:model.blur="NoOfPeople" /></div>
                            </div>
                            <x-field label="Notes (optional)" name="bookingnotes" textarea rows="2" wire:model.blur="bookingnotes" placeholder="Flight number, luggage, child seats…" />
                            @if ($loggedIn)
                                <button class="btn btn-accent btn-lg w-100" type="submit" @disabled($fare === null)>
                                    <span wire:loading.remove wire:target="book">Book transfer</span>
                                    <span wire:loading wire:target="book"><span class="spinner-border spinner-border-sm me-2"></span>Booking…</span>
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
                            ['Route', $pickuppoint === 'From Airport' ? "{$airportfield} Airport → {$Destination}" : "{$Destination} → {$airportfield} Airport"],
                            ['When', \Illuminate\Support\Carbon::parse($checkindate ?: now())->format('D j M') . ($pickuptime ? ' · ' . $pickuptime : '')],
                            ['Passengers', (int) $NoOfPeople],
                        ],
                        'total' => $fare,
                        'showTotal' => true,
                    ])
                </aside>
            </div>
        </div>
    </section>
</div>

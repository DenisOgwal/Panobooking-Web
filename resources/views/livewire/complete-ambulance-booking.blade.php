@php
    use App\Support\Vehicles;
    $vehicle = $this->vehicle;
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
@endphp
<div>
    <x-page-hero title="Request an ambulance" :subtitle="($vehicle->AmbulanceCategory ?: 'Ambulance') . ' · ' . $vehicle->TaxiNo"
                 :crumbs="[['Ambulance', '/ambulance'], [$vehicle->TaxiNo, Vehicles::detailUrl('ambulance', $vehicle)], ['Request']]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="alert alert-danger d-flex gap-2 align-items-start" role="note">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>If someone's life is in danger, <strong>call your local emergency number now</strong>. This form sends a request to the ambulance provider.</div>
            </div>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <h2 class="h4 mb-3">Where should the crew go?</h2>
                        <form wire:submit="book" novalidate>
                            <div class="row g-3">
                                <div class="col-sm-6"><x-field label="Area or town" name="Area" wire:model.blur="Area" icon="bi-geo-alt" /></div>
                                <div class="col-sm-6"><x-field label="Exact pick-up point" name="pickuppoint" wire:model.blur="pickuppoint" icon="bi-pin-map" placeholder="Building, street, landmark" /></div>
                                <div class="col-sm-6"><x-field label="Date" name="checkindate" type="date" wire:model.live="checkindate" :min="now()->toDateString()" icon="bi-calendar-event" /></div>
                                <div class="col-sm-6"><x-field label="Time" name="Pickuptime" type="time" wire:model.blur="Pickuptime" icon="bi-clock" /></div>
                            </div>
                            <x-field label="Reason for the request" name="reason" textarea rows="3" wire:model.blur="reason" placeholder="Symptoms or situation, e.g. patient transfer to Mulago hospital" />
                            <x-field label="Notes (optional)" name="bookingnotes" textarea rows="2" wire:model.blur="bookingnotes" placeholder="Contact person on site, access instructions" />
                            @if ($loggedIn)
                                <button class="btn btn-danger btn-lg w-100" type="submit">
                                    <span wire:loading.remove wire:target="book">Send request</span>
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
                        'lines' => array_values(array_filter([
                            $vehicle->TelephoneNumber ? ['Crew phone', $vehicle->TelephoneNumber] : null,
                            ['Based in', $vehicle->CurrentLocation ?: $vehicle->City],
                        ])),
                    ])
                </aside>
            </div>
        </div>
    </section>
</div>

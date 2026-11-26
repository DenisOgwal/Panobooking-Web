@php
    use App\Support\Media;
    $house = $this->house;
    $rules = $house->houserulesdetail;
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $currency = app(\App\Support\Currency::class);
@endphp
<div>
    <x-page-hero title="Book your home stay" :subtitle="$house->GuestHouseName . ', ' . $house->City"
                 :crumbs="[['Home stays', '/homeguests'], [$house->GuestHouseName, route('HomeGuestSingle', ['homeguestslug' => $house->IDs])], ['Book']]" />

    <section class="section-sm">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <h2 class="h4 mb-3">Your stay</h2>
                        <form wire:submit="submitfeedback" novalidate>
                            <div class="row g-3">
                                <div class="col-sm-6"><x-field label="Check-in" name="checkindate" type="date" wire:model.live="checkindate" :min="now()->toDateString()" icon="bi-calendar-event" /></div>
                                <div class="col-sm-6"><x-field label="Check-out" name="checkoutdate" type="date" wire:model.live="checkoutdate" :min="$checkindate ?: now()->toDateString()" icon="bi-calendar-check" /></div>
                                <div class="col-sm-6"><x-field label="Children" name="Childrenno" type="number" min="0" max="20" wire:model.blur="Childrenno" icon="bi-emoji-smile" /></div>
                            </div>
                            <x-field label="Notes for your host" name="BookingDescription" textarea rows="3" wire:model.blur="BookingDescription"
                                     placeholder="Arrival time, dietary needs, anything your host should know" />
                            @if ($loggedIn)
                                <button class="btn btn-accent btn-lg w-100" type="submit" @disabled($this->nights < 1)>
                                    <span wire:loading.remove wire:target="submitfeedback">Book now</span>
                                    <span wire:loading wire:target="submitfeedback"><span class="spinner-border spinner-border-sm me-2"></span>Booking…</span>
                                </button>
                                <p class="small text-muted mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>No payment now. Your host confirms first.</p>
                            @endif
                        </form>
                        @unless ($loggedIn)
                            <div class="mt-3">@include('livewire.partials.signin-card')</div>
                        @endunless
                    </div>
                </div>

                <aside class="col-lg-5">
                    <div class="booking-box">
                        <div class="d-flex gap-3 mb-3">
                            <img class="rounded-3 flex-shrink-0 object-fit-cover" width="112" height="84" src="{{ Media::image('guesthouse', $house) }}" data-fallback="{{ Media::placeholder() }}" alt="">
                            <div>
                                <div class="small text-muted">{{ $house->City }}</div>
                                <h2 class="h5 mb-1">{{ $house->GuestHouseName }}</h2>
                                <x-rating :value="$house->Rating" />
                            </div>
                        </div>
                        <div class="summary-line"><span>{{ $currency->format($house->Prices) }} × {{ $this->nights }} {{ \Illuminate\Support\Str::plural('night', $this->nights) }}</span><span>{{ $currency->format($this->total) }}</span></div>
                        @if ($rules?->CheckIn)<div class="summary-line"><span>Check-in</span><span>{{ $rules->CheckIn }}</span></div>@endif
                        @if ($rules?->CheckOut)<div class="summary-line"><span>Check-out</span><span>{{ $rules->CheckOut }}</span></div>@endif
                        <div class="total"><span>Total</span><x-price :amount="$this->total" size="lg" /></div>
                        @if ($rules?->PaymentCancelation)
                            <p class="small text-muted mt-3 mb-0"><i class="bi bi-shield-check text-success me-1"></i>{{ $rules->PaymentCancelation }}</p>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

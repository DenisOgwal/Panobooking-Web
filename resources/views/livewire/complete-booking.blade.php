@php
    use App\Support\Features;
    use App\Support\Media;
    $room = $this->room;
    $hotel = $room->hotel;
    $loggedIn = app(\App\Support\CustomerAuth::class)->check();
    $currency = app(\App\Support\Currency::class);
@endphp
<div>
    <x-page-hero title="Complete your booking" :subtitle="$room->RoomName . ' at ' . $room->HotelName"
                 :crumbs="array_filter([$hotel ? [$hotel->Place, route('HotelSingle', ['hotelslug' => $hotel->IDs])] : null, ['Book']])" />

    <section class="section-sm">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <h2 class="h4 mb-3">Your stay</h2>
                        <form wire:submit="submitfeedback" novalidate>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <x-field label="Check-in" name="checkindate" type="date" wire:model.live="checkindate" :min="now()->toDateString()" icon="bi-calendar-event" />
                                </div>
                                <div class="col-sm-6">
                                    <x-field label="Check-out" name="checkoutdate" type="date" wire:model.live="checkoutdate" :min="$checkindate ?: now()->toDateString()" icon="bi-calendar-check" />
                                </div>
                                <div class="col-sm-6">
                                    <x-field label="Rooms" name="Destinationquantity" type="number" min="1" :max="max(1, (int) $room->NoOfRooms)" wire:model.live.debounce.300ms="Destinationquantity"
                                             icon="bi-door-open" :hint="(int) $room->NoOfRooms . ' of this type available'" />
                                </div>
                                <div class="col-sm-6">
                                    <x-field label="Children" name="Childrenno" type="number" min="0" max="20" wire:model.blur="Childrenno" icon="bi-emoji-smile" />
                                </div>
                            </div>

                            @if ($loggedIn)
                                <button class="btn btn-accent btn-lg w-100 mt-2" type="submit" @disabled($this->nights < 1)>
                                    <span wire:loading.remove wire:target="submitfeedback">Reserve now</span>
                                    <span wire:loading wire:target="submitfeedback"><span class="spinner-border spinner-border-sm me-2"></span>Reserving…</span>
                                </button>
                                <p class="small text-muted mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>No payment now. The property confirms your booking and we'll email you.</p>
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
                            <img class="rounded-3 flex-shrink-0 object-fit-cover" width="112" height="84" src="{{ Media::image('room', $room) }}"
                                 data-fallback="{{ Media::placeholder() }}" alt="">
                            <div>
                                <div class="small text-muted">{{ $room->HotelName }}</div>
                                <h2 class="h5 mb-1">{{ $room->RoomName }}</h2>
                                @if ($hotel)<x-rating :value="$hotel->Rating" />@endif
                            </div>
                        </div>

                        @php $perks = Features::enabled($room->roomdetails, ['FreeWifi', 'AirConditioning', 'AttachedBathroom', 'Balcony', 'FlatScreen', 'MiniBar']); @endphp
                        @if ($perks)
                            <div class="tag-list mb-3">
                                @foreach (array_keys($perks) as $perk)<span class="tag"><i class="bi bi-check2"></i>{{ $perk }}</span>@endforeach
                            </div>
                        @endif

                        <div class="summary-line"><span>{{ $currency->format($room->Price) }} × {{ $this->nights }} {{ \Illuminate\Support\Str::plural('night', $this->nights) }}</span><span>{{ $currency->format($room->Price * $this->nights) }}</span></div>
                        <div class="summary-line"><span>Rooms</span><span>× {{ max(0, (int) $Destinationquantity) }}</span></div>
                        @if (filled($room->BreakFast))<div class="summary-line"><span>Breakfast</span><span class="text-success">{{ $room->BreakFast }}</span></div>@endif
                        <div class="total">
                            <span>Total</span>
                            <x-price :amount="$this->total" size="lg" />
                        </div>
                        @if (filled($room->RefundPolicy))
                            <p class="small text-muted mt-3 mb-0"><i class="bi bi-shield-check text-success me-1"></i>{{ $room->RefundPolicy }}</p>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

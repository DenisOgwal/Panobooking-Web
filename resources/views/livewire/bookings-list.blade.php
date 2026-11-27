@php use Illuminate\Support\Carbon; use Illuminate\Support\Str; @endphp
<div>
    <x-page-hero :title="$title" :subtitle="$subtitle" :crumbs="[['My bookings'], [$title]]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a @class(['nav-link', 'active' => $pending]) href="/PendingBookings"><i class="bi bi-hourglass-split me-1"></i>Pending</a></li>
                    <li class="nav-item"><a @class(['nav-link', 'active' => ! $pending]) href="/Bookings"><i class="bi bi-check2-circle me-1"></i>History</a></li>
                </ul>
                <div class="input-icon">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label class="visually-hidden" for="booking-search">Search bookings</label>
                    <input id="booking-search" class="form-control" type="search" placeholder="Search bookings" wire:model.live.debounce.300ms="search">
                </div>
            </div>

            <div wire:loading.class="opacity-50">
                @forelse ($bookings as $b)
                    <article class="room-card align-items-center" wire:key="booking-{{ $b->IDs }}">
                        <div class="d-flex align-items-center gap-3">
                            <span class="category-chip p-0 border-0 bg-transparent"><span class="icon"><i class="bi {{ $b->type_icon }}"></i></span></span>
                            <div>
                                <div class="small text-muted">{{ $b->BookingType ?: 'Booking' }}</div>
                                <strong>{{ $b->Facility }}</strong>
                            </div>
                        </div>
                        <div>
                            <h3 class="h6 mb-1">{{ $b->ProductName }} @if ($b->Quantity > 1)<span class="text-muted">× {{ $b->Quantity }}</span>@endif</h3>
                            <div class="small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ Str::of($b->DatesFrom)->before(' 00:00:00') }}@if ($b->DatesTo && $b->DatesTo !== 'N/A') → {{ Str::of($b->DatesTo)->before(' 00:00:00') }}@endif
                                <span class="mx-1">·</span>Requested {{ $b->Date ? Carbon::parse($b->Date)->format('j M Y') : $b->created_at?->format('j M Y') }}
                            </div>
                            @if (filled($b->Descriptions))<div class="small mt-1">{{ Str::limit($b->Descriptions, 140) }}</div>@endif
                            @if (! $pending && filled($b->ConfirmReason))<div @class(['small mt-1', 'text-success' => $b->Confirmed !== \App\Models\CartModel::DECLINED, 'text-muted' => $b->Confirmed === \App\Models\CartModel::DECLINED])><i class="bi bi-chat-left-text me-1"></i>{{ $b->ConfirmReason }}</div>@endif
                        </div>
                        <div class="room-aside">
                            @if ((float) $b->Cost > 0)
                                <x-price :amount="$b->Cost" />
                            @else
                                <span class="text-muted small">No charge</span>
                            @endif
                            @if ($pending)
                                <span class="badge text-bg-warning">Awaiting confirmation</span>
                                <button type="button" class="btn btn-outline-danger btn-sm" wire:click="cancel({{ $b->IDs }})"
                                        wire:confirm="Cancel this booking? This cannot be undone.">Cancel</button>
                            @elseif ($b->Confirmed === \App\Models\CartModel::DECLINED)
                                <span class="badge text-bg-secondary">Declined</span>
                            @else
                                <span class="badge text-bg-success">Confirmed</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <x-empty-state :icon="$pending ? 'bi-hourglass' : 'bi-clock-history'" :title="$search !== '' ? 'No bookings match “' . $search . '”' : ($pending ? 'No pending bookings' : 'No booking history yet')">
                        <a href="/">Find your next stay</a>
                    </x-empty-state>
                @endforelse
                <div class="mt-4">{{ $bookings->links() }}</div>
            </div>
        </div>
    </section>
</div>

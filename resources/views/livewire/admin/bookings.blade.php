@php use App\Admin\Column; use App\Models\CartModel; use Illuminate\Support\Str; @endphp
<div>
    <div class="admin-page-head">
        <div>
            <h1 class="h3 mb-0">Bookings</h1>
            <div class="text-muted small">{{ number_format($bookings->total()) }} shown · prices in UGX</div>
        </div>
    </div>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item"><button type="button" @class(['nav-link', 'active' => $status === '']) wire:click="$set('status', '')">All <span class="badge text-bg-light ms-1">{{ $counts->sum() }}</span></button></li>
        @foreach (CartModel::STATUSES as $s)
            <li class="nav-item"><button type="button" @class(['nav-link', 'active' => $status === $s]) wire:click="$set('status', '{{ $s }}')">{{ $s }} <span class="badge text-bg-light ms-1">{{ $counts[$s] ?? 0 }}</span></button></li>
        @endforeach
    </ul>

    <div class="admin-card">
        <div class="admin-toolbar">
            <div class="input-icon flex-grow-1">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label class="visually-hidden" for="booking-search">Search</label>
                <input id="booking-search" class="form-control" type="search" placeholder="Customer, email, facility, order code…" wire:model.live.debounce.300ms="search">
            </div>
            <div>
                <label class="visually-hidden" for="booking-type">Type</label>
                <select id="booking-type" class="form-select" wire:model.live="type">
                    <option value="">Type: all</option>
                    @foreach ($types as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                </select>
            </div>
            <div class="d-flex align-items-center gap-1">
                <label class="small text-muted" for="booking-from">From</label>
                <input id="booking-from" type="date" class="form-control" wire:model.live="from">
                <label class="small text-muted" for="booking-to">to</label>
                <input id="booking-to" type="date" class="form-control" wire:model.live="to">
            </div>
            @if ($search !== '' || $type !== '' || $from !== '' || $to !== '' || $status !== '')
                <button type="button" class="btn btn-link" wire:click="clearFilters">Clear</button>
            @endif
        </div>

        <div class="table-responsive" wire:loading.class="opacity-50">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Booking</th>
                        <th scope="col">Dates</th>
                        <th scope="col" class="text-end">Cost</th>
                        <th scope="col">Status</th>
                        <th scope="col">Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr wire:key="b-{{ $b->IDs }}">
                            <td><a class="fw-semibold" href="{{ route('admin.bookings.show', $b->IDs) }}">{{ $b->IDs }}</a></td>
                            <td>
                                <div class="fw-semibold">{{ $b->UserNames ?: '—' }}</div>
                                <div class="small text-muted">{{ $b->User }}</div>
                            </td>
                            <td>
                                <div class="small text-muted"><i class="bi {{ $b->type_icon }} me-1"></i>{{ $b->BookingType ?: 'Stay' }}</div>
                                <a href="{{ route('admin.bookings.show', $b->IDs) }}">{{ $b->Facility }}</a>
                                @if ($b->ProductName)<div class="small">{{ Str::limit($b->ProductName, 50) }} @if ($b->Quantity > 1)× {{ $b->Quantity }}@endif</div>@endif
                            </td>
                            <td class="small text-nowrap">
                                {{ Str::of($b->DatesFrom)->before(' 00:00:00') ?: '—' }}@if ($b->DatesTo && $b->DatesTo !== 'N/A')<br>→ {{ Str::of($b->DatesTo)->before(' 00:00:00') }}@endif
                            </td>
                            <td class="text-end text-nowrap">{{ (float) $b->Cost > 0 ? number_format((float) $b->Cost) : '—' }}</td>
                            <td><span class="badge text-bg-{{ Column::badgeTone((string) $b->Confirmed) }}">{{ $b->Confirmed }}</span></td>
                            <td class="small text-muted text-nowrap">{{ ($b->Date ? \Illuminate\Support\Carbon::parse($b->Date) : $b->created_at)?->format('j M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-empty-state title="No bookings match" icon="bi-receipt" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())
            <div class="admin-card-foot">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>

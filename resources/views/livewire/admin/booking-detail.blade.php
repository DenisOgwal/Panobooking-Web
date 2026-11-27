@php use App\Admin\Column; use App\Models\CartModel; use Illuminate\Support\Carbon; use Illuminate\Support\Str; @endphp
<div>
    <div class="admin-page-head">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings') }}">Bookings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $b->IDs }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">{{ $b->Facility ?: 'Booking' }} <span class="badge text-bg-{{ Column::badgeTone((string) $b->Confirmed) }} align-middle fs-6">{{ $b->Confirmed }}</span></h1>
            <div class="text-muted small"><i class="bi {{ $b->type_icon }} me-1"></i>{{ $b->BookingType ?: 'Stay' }} · requested {{ ($b->Date ? Carbon::parse($b->Date) : $b->created_at)?->format('j M Y, H:i') }}</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <section class="admin-card admin-card-body">
                <h2 class="h6 admin-section-title">Booking</h2>
                <dl class="admin-dl">
                    <dt>Facility</dt><dd>{{ $b->Facility ?: '—' }}</dd>
                    <dt>Item</dt><dd>{{ $b->ProductName ?: '—' }}</dd>
                    <dt>Quantity</dt><dd>{{ $b->Quantity ?: 1 }}</dd>
                    <dt>From</dt><dd>{{ Str::of($b->DatesFrom)->before(' 00:00:00') ?: '—' }}</dd>
                    <dt>To</dt><dd>{{ $b->DatesTo && $b->DatesTo !== 'N/A' ? Str::of($b->DatesTo)->before(' 00:00:00') : '—' }}</dd>
                    <dt>Cost</dt><dd>{{ (float) $b->Cost > 0 ? 'UGX ' . number_format((float) $b->Cost) : 'No charge' }}
                        @if ($b->Currency && $b->Currency !== 'UGX' && $b->CurrencyAmount)<span class="text-muted">(shown to customer as {{ $b->Currency }} {{ number_format((float) $b->CurrencyAmount, 2) }})</span>@endif</dd>
                    <dt>Order code</dt><dd>{{ $b->OrderCode ?: '—' }}</dd>
                    <dt>Details</dt><dd class="admin-pre">{{ $b->Descriptions ?: '—' }}</dd>
                </dl>
            </section>

            <section class="admin-card admin-card-body mt-4">
                <h2 class="h6 admin-section-title">Customer</h2>
                <dl class="admin-dl">
                    <dt>Name</dt><dd>{{ $b->UserNames ?: ($customer?->FullNames ?? '—') }}</dd>
                    <dt>Email</dt><dd>@if ($b->User)<a href="mailto:{{ $b->User }}">{{ $b->User }}</a>@else — @endif</dd>
                    <dt>Phone</dt><dd>@if ($customer?->Telephone)<a href="tel:{{ $customer->Telephone }}">{{ $customer->Telephone }}</a>@else — @endif</dd>
                    <dt>Account</dt><dd>
                        @if ($customer)
                            <a href="{{ route('admin.resources.edit', ['resource' => 'customers', 'id' => $customer->id]) }}">{{ $customer->ApprovalStatus ?: 'Not approved' }} account</a>
                            · <a href="{{ route('admin.bookings', ['q' => $b->User]) }}">all their bookings</a>
                        @else
                            No account found
                        @endif
                    </dd>
                </dl>
            </section>
        </div>

        <div class="col-xl-4">
            <section class="admin-card admin-card-body">
                <h2 class="h6 admin-section-title">Answer the request</h2>
                <label class="form-label" for="note">Note to the customer</label>
                <textarea id="note" rows="4" @class(['form-control', 'is-invalid' => $errors->has('note')]) wire:model="note"
                          placeholder="e.g. Confirmed. Your driver will meet you at arrivals."></textarea>
                @error('note')<div class="form-error">{{ $message }}</div>@enderror
                <div class="form-text mb-3">Shown on the customer's booking history.</div>

                <div class="d-grid gap-2">
                    @if ($b->Confirmed !== CartModel::CONFIRMED)
                        <button type="button" class="btn btn-success" wire:click="confirm"><i class="bi bi-check2-circle me-1"></i>Confirm booking</button>
                    @endif
                    @if ($b->Confirmed !== CartModel::DECLINED)
                        <button type="button" class="btn btn-outline-secondary" wire:click="decline"><i class="bi bi-x-circle me-1"></i>Decline</button>
                    @endif
                    @if ($b->Confirmed !== CartModel::PENDING)
                        <button type="button" class="btn btn-link" wire:click="reopen">Move back to pending</button>
                    @endif
                    @if ($canManage)
                        <button type="button" class="btn btn-outline-danger mt-2" wire:click="delete"
                                wire:confirm="Delete booking #{{ $b->IDs }}? This cannot be undone."><i class="bi bi-trash me-1"></i>Delete booking</button>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>

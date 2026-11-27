@php use App\Admin\Column; use Illuminate\Support\Str; @endphp
<div>
    <div class="admin-page-head">
        <div>
            <h1 class="h3 mb-0">Dashboard</h1>
            <div class="text-muted small">{{ now()->format('l j F Y') }}</div>
        </div>
    </div>

    <div class="admin-stats">
        <a class="admin-stat" href="{{ route('admin.bookings', ['status' => 'Pending']) }}">
            <span class="admin-stat-label"><i class="bi bi-hourglass-split"></i>Bookings to answer</span>
            <span class="admin-stat-value">{{ number_format($pendingBookings) }}</span>
        </a>
        <a class="admin-stat" href="{{ route('admin.bookings', ['from' => now()->startOfMonth()->toDateString()]) }}">
            <span class="admin-stat-label"><i class="bi bi-receipt"></i>Bookings this month</span>
            <span class="admin-stat-value">{{ number_format($bookingsThisMonth) }}</span>
        </a>
        <div class="admin-stat">
            <span class="admin-stat-label"><i class="bi bi-cash-stack"></i>Confirmed this month</span>
            <span class="admin-stat-value"><small>UGX</small> {{ number_format($revenueThisMonth) }}</span>
        </div>
        <a class="admin-stat" href="{{ route('admin.resources.index', ['resource' => 'customers']) }}">
            <span class="admin-stat-label"><i class="bi bi-person-plus"></i>New customers (30 days)</span>
            <span class="admin-stat-value">{{ number_format($newCustomers) }}</span>
        </a>
    </div>

    <div class="row g-4 mt-0">
        <div class="col-xl-8">
            <section class="admin-card">
                <div class="admin-card-head">
                    <h2 class="h6 mb-0">Recent bookings</h2>
                    <a class="small" href="{{ route('admin.bookings') }}">All bookings</a>
                </div>
                <div class="table-responsive">
                    <table class="table admin-table align-middle mb-0">
                        <tbody>
                            @forelse ($recentBookings as $b)
                                <tr>
                                    <td class="text-muted small">#{{ $b->IDs }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $b->IDs) }}" class="fw-semibold">{{ $b->Facility }}</a>
                                        <div class="small text-muted"><i class="bi {{ $b->type_icon }} me-1"></i>{{ $b->BookingType ?: 'Stay' }} · {{ $b->UserNames ?: $b->User }}</div>
                                    </td>
                                    <td class="text-end text-nowrap small">{{ (float) $b->Cost > 0 ? 'UGX ' . number_format((float) $b->Cost) : '' }}</td>
                                    <td class="text-end"><span class="badge text-bg-{{ Column::badgeTone((string) $b->Confirmed) }}">{{ $b->Confirmed }}</span></td>
                                </tr>
                            @empty
                                <tr><td><x-empty-state title="No bookings yet" icon="bi-receipt" /></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-card mt-4">
                <div class="admin-card-head"><h2 class="h6 mb-0">Records</h2></div>
                <div class="admin-card-body">
                    <div class="admin-totals">
                        @foreach ($totals as $t)
                            <a href="{{ $t['url'] }}" class="admin-total">
                                <i class="bi {{ $t['icon'] }}" aria-hidden="true"></i>
                                <span>{{ $t['label'] }}</span>
                                <strong>{{ number_format($t['count']) }}</strong>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <div class="col-xl-4">
            <section class="admin-card">
                <div class="admin-card-head"><h2 class="h6 mb-0">Waiting for approval</h2></div>
                <ul class="list-group list-group-flush">
                    @foreach ($pending as $p)
                        <li class="list-group-item d-flex align-items-center gap-2">
                            <i class="bi {{ $p['icon'] }} text-muted" aria-hidden="true"></i>
                            <a href="{{ $p['url'] }}">{{ $p['label'] }}</a>
                            <span @class(['badge ms-auto', 'text-bg-warning' => $p['count'] > 0, 'text-bg-light' => $p['count'] === 0])>{{ $p['count'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="admin-card mt-4">
                <div class="admin-card-head">
                    <h2 class="h6 mb-0">New customers</h2>
                    <a class="small" href="{{ route('admin.resources.index', ['resource' => 'customers']) }}">All</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentCustomers as $c)
                        <li class="list-group-item">
                            <a href="{{ route('admin.resources.edit', ['resource' => 'customers', 'id' => $c->id]) }}" class="fw-semibold">{{ $c->FullNames }}</a>
                            @if ($c->ApprovalStatus !== $approvedValue)<span class="badge text-bg-warning ms-1">Awaiting approval</span>@endif
                            <div class="small text-muted">{{ $c->Email }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">No customers yet.</li>
                    @endforelse
                </ul>
            </section>

            <section class="admin-card mt-4">
                <div class="admin-card-head">
                    <h2 class="h6 mb-0">Latest messages</h2>
                    <a class="small" href="{{ route('admin.resources.index', ['resource' => 'messages']) }}">All</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentMessages as $m)
                        <li class="list-group-item">
                            <a href="{{ route('admin.resources.edit', ['resource' => 'messages', 'id' => $m->id]) }}" class="fw-semibold">{{ $m->Names ?: $m->Email }}</a>
                            <div class="small text-muted">{{ Str::limit($m->Messages, 90) }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">No messages yet.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</div>

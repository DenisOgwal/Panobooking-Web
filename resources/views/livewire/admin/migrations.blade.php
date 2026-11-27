<div>
    <div class="admin-page-head">
        <div>
            <h1 class="h3 mb-0">Database migrations</h1>
            <div class="text-muted small">{{ $driver }} · {{ $database }} · {{ $total }} migrations, {{ $pending }} pending</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-secondary" wire:click="preview" wire:loading.attr="disabled">
                <i class="bi bi-eye me-1"></i>Preview SQL
            </button>
            <button type="button" class="btn btn-primary" wire:click="run" wire:loading.attr="disabled"
                    wire:confirm="Run {{ $pending }} pending migration(s) on {{ $database }}?">
                <span wire:loading.remove wire:target="run"><i class="bi bi-play-fill me-1"></i>Run pending migrations</span>
                <span wire:loading wire:target="run"><span class="spinner-border spinner-border-sm me-2"></span>Running…</span>
            </button>
        </div>
    </div>

    <div class="alert alert-info small">
        Every migration checks what already exists (tables, columns, indexes) before changing anything, so running them is
        safe to repeat. Against a database that already has these tables, they are simply recorded as run. Only pending
        migrations run; nothing is rolled back from here.
    </div>

    @if ($output !== '')
        <section @class(['admin-card admin-card-body mb-4', 'border-danger' => ! $outputOk])>
            <h2 class="h6 admin-section-title">{{ $outputTitle }}</h2>
            <pre class="admin-console mb-0">{{ $output }}</pre>
        </section>
    @endif

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr><th scope="col">Migration</th><th scope="col">Status</th><th scope="col" class="text-end">Batch</th></tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr wire:key="m-{{ $row['name'] }}">
                            <td class="font-monospace small">{{ $row['name'] }}</td>
                            <td>
                                @if ($row['ran'])
                                    <span class="badge text-bg-success">Ran</span>
                                @else
                                    <span class="badge text-bg-warning">Pending</span>
                                @endif
                            </td>
                            <td class="text-end text-muted small">{{ $row['batch'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

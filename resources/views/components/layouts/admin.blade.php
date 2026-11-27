@props(['title' => null])
@php
    use App\Admin\ResourceRegistry;
    $nav = ResourceRegistry::navigation();
    $currentResource = request()->route('resource');
    $user = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ? $title . ' · Admin' : 'Admin' }} · Panobooking</title>
    <link rel="icon" href="{{ asset('images/panaicon.png') }}" type="image/png">
    @vite(['resources/scss/admin.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="admin-body">
    <a href="#admin-main" class="visually-hidden-focusable position-absolute top-0 start-0 m-2 btn btn-primary">Skip to content</a>

    <div class="admin-shell">
        <aside class="admin-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="admin-nav" aria-label="Admin navigation">
            <div class="admin-brand">
                <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/panologo.png') }}" alt="Panobooking"></a>
                <span class="badge text-bg-dark">Admin</span>
                <button type="button" class="btn-close d-lg-none ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#admin-nav" aria-label="Close menu"></button>
            </div>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" @class(['admin-nav-link', 'active' => request()->routeIs('admin.dashboard')])>
                    <i class="bi bi-speedometer2" aria-hidden="true"></i>Dashboard
                </a>
                <a href="{{ route('admin.bookings') }}" @class(['admin-nav-link', 'active' => request()->routeIs('admin.bookings*')])>
                    <i class="bi bi-receipt" aria-hidden="true"></i>Bookings
                </a>
                @foreach ($nav as $group => $resources)
                    <div class="admin-nav-group">{{ $group }}</div>
                    @foreach ($resources as $res)
                        <a href="{{ route('admin.resources.index', ['resource' => $res::slug()]) }}"
                           @class(['admin-nav-link', 'active' => $currentResource === $res::slug() || (request()->routeIs('admin.property-details') && in_array($res::slug(), [request()->route('type') === 'hotel' ? 'properties' : 'home-stays']))])>
                            <i class="bi {{ $res::icon() }}" aria-hidden="true"></i>{{ $res::label() }}
                        </a>
                    @endforeach
                @endforeach
                @can('admin.develop')
                    <div class="admin-nav-group">Developer</div>
                    <a href="{{ route('admin.migrations') }}" @class(['admin-nav-link', 'active' => request()->routeIs('admin.migrations')])>
                        <i class="bi bi-database-gear" aria-hidden="true"></i>Database migrations
                    </a>
                @endcan
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#admin-nav" aria-controls="admin-nav">
                    <i class="bi bi-list" aria-hidden="true"></i><span class="visually-hidden">Menu</span>
                </button>
                <a class="small text-muted d-none d-sm-inline" href="/" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right me-1"></i>View site</a>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <div class="text-end lh-sm d-none d-sm-block">
                        <div class="fw-semibold small">{{ $user?->name }}</div>
                        <div class="text-muted small">{{ \App\Models\User::ROLES[$user?->role] ?? '' }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-box-arrow-right me-1"></i>Sign out</button>
                    </form>
                </div>
            </header>

            <main id="admin-main" class="admin-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div id="toast-stack" class="toast-stack" aria-live="polite"></div>
    @if (session()->has('alert'))
        <script type="application/json" data-flash-alert>@json(session('alert'))</script>
    @endif

    @livewireScripts
</body>
</html>

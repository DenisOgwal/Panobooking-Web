@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' · Panobooking' : 'Panobooking · Stays, car hire, airport taxis and ambulances in Uganda' }}</title>
    <meta name="description" content="{{ $description ?? 'Book hotels, guest houses, rental cars, airport taxis, ambulances and tours across Uganda.' }}">
    <meta name="theme-color" content="#1e90ff">
    <link rel="icon" href="{{ asset('images/panaicon.png') }}" type="image/png">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <a href="#main" class="visually-hidden-focusable position-absolute top-0 start-0 m-2 btn btn-primary">Skip to content</a>

    <x-site-header />

    <main id="main">
        {{ $slot }}
    </main>

    <x-site-footer />

    <div id="toast-stack" class="toast-stack" aria-live="polite"></div>
    @if (session()->has('alert'))
        <script type="application/json" data-flash-alert>@json(session('alert'))</script>
    @endif

    @livewireScripts
</body>
</html>

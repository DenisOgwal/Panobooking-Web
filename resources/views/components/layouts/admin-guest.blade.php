@props(['title' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ? $title . ' · ' : '' }}Panobooking admin</title>
    <link rel="icon" href="{{ asset('images/panaicon.png') }}" type="image/png">
    @vite(['resources/scss/admin.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="admin-body admin-guest">
    <main id="admin-main">
        {{ $slot }}
    </main>
    <div id="toast-stack" class="toast-stack" aria-live="polite"></div>
    @if (session()->has('alert'))
        <script type="application/json" data-flash-alert>@json(session('alert'))</script>
    @endif
    @livewireScripts
</body>
</html>

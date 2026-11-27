<div class="admin-login">
    <div class="admin-login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('images/panologo.png') }}" alt="Panobooking" class="admin-login-logo">
            <h1 class="h4 mt-3 mb-1">Admin sign in</h1>
            <p class="text-muted small mb-0">For Panobooking staff. Customers sign in <a href="{{ route('Login') }}">here</a>.</p>
        </div>
        <form wire:submit="login" novalidate>
            <x-field label="Email" name="email" type="email" icon="bi-envelope" autocomplete="username" wire:model="email" autofocus />
            <x-field label="Password" name="password" type="password" icon="bi-lock" autocomplete="current-password" wire:model="password" />
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                <label class="form-check-label" for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="login">Sign in</span>
                <span wire:loading wire:target="login">Signing in…</span>
            </button>
        </form>
    </div>
</div>

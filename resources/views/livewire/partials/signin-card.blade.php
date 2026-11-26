{{-- Inline sign-in for listing pages (component must use SignsInInline). --}}
<div class="border rounded-3 p-3 bg-canvas">
    <p class="fw-semibold mb-2"><i class="bi bi-lock me-1"></i>Sign in to book</p>
    <form wire:submit="signin" novalidate>
        <x-field label="Email" name="clientemail" type="email" wire:model="clientemail" autocomplete="email" icon="bi-envelope" />
        <x-field label="Password" name="password" type="password" wire:model="password" autocomplete="current-password" icon="bi-key" />
        <button class="btn btn-primary w-100" type="submit">
            <span wire:loading.remove wire:target="signin">Sign in</span>
            <span wire:loading wire:target="signin"><span class="spinner-border spinner-border-sm me-2"></span>Signing in…</span>
        </button>
    </form>
    <p class="small text-muted mt-2 mb-0">New here? <a href="/Register">Create an account</a></p>
</div>

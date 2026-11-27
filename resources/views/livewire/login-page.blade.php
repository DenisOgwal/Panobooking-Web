<div>
    <section class="section">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="form-card">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/panaicon.png') }}" alt="" width="56" height="56" class="mb-3">
                            <h1 class="h3 mb-1">Welcome back</h1>
                            <p class="text-muted mb-0">Sign in to book and manage your trips.</p>
                        </div>
                        <form wire:submit="signin" novalidate>
                            <x-field label="Email" name="email" type="email" wire:model="email" autocomplete="email" icon="bi-envelope" autofocus />
                            <x-field label="Password" name="password" type="password" wire:model="password" autocomplete="current-password" icon="bi-key" />
                            @if ($unverified)
                                <div class="alert alert-warning small" role="alert">
                                    <a href="{{ route('VerifyEmail') }}" class="alert-link">Enter your verification code</a> or ask for a new one.
                                </div>
                            @endif
                            <p class="text-end small mt-n2"><a href="{{ route('ForgotPassword') }}">Forgot your password?</a></p>
                            <button class="btn btn-primary btn-lg w-100" type="submit">
                                <span wire:loading.remove wire:target="signin">Sign in</span>
                                <span wire:loading wire:target="signin"><span class="spinner-border spinner-border-sm me-2"></span>Signing in…</span>
                            </button>
                        </form>
                        <p class="text-center text-muted small mt-4 mb-0">New to Panobooking? <a href="/Register">Create an account</a></p>
                    </div>
                    <p class="text-center small text-muted mt-3">
                        By continuing you agree to our <a href="/TermsAndConditions">terms</a> and <a href="/privacypolicy">privacy policy</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<div>
    <section class="section">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="form-card">
                        <div class="text-center mb-4">
                            <i class="bi bi-envelope-check display-6 text-primary" aria-hidden="true"></i>
                            <h1 class="h3 mt-2 mb-1">Verify your email</h1>
                            <p class="text-muted mb-0">Enter the 6-digit code we emailed you when you signed up.</p>
                        </div>
                        <form wire:submit="verify" novalidate>
                            <x-field label="Email" name="email" type="email" wire:model="email" autocomplete="email" icon="bi-envelope" />
                            <x-field label="Verification code" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code" wire:model="code" icon="bi-shield-check" />
                            <button class="btn btn-primary btn-lg w-100" type="submit">
                                <span wire:loading.remove wire:target="verify">Verify email</span>
                                <span wire:loading wire:target="verify"><span class="spinner-border spinner-border-sm me-2"></span>Checking…</span>
                            </button>
                        </form>
                        <p class="text-center text-muted small mt-4 mb-0">
                            No email? Check your spam folder, or
                            <button type="button" class="btn btn-link btn-sm p-0 align-baseline" wire:click="resend">send a new code</button>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

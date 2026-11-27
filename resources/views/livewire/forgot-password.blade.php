<div>
    <section class="section">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="form-card">
                        <div class="text-center mb-4">
                            <i class="bi bi-key display-6 text-primary" aria-hidden="true"></i>
                            <h1 class="h3 mt-2 mb-1">Reset your password</h1>
                            <p class="text-muted mb-0">
                                @if ($codeSent)
                                    If <strong>{{ $email }}</strong> has an account, we've emailed it a 6-digit code.
                                @else
                                    We'll email you a code to choose a new password.
                                @endif
                            </p>
                        </div>
                        @if (! $codeSent)
                            <form wire:submit="sendCode" novalidate>
                                <x-field label="Email" name="email" type="email" wire:model="email" autocomplete="email" icon="bi-envelope" autofocus />
                                <button class="btn btn-primary btn-lg w-100" type="submit">Email me a code</button>
                            </form>
                        @else
                            <form wire:submit="resetPassword" novalidate>
                                <x-field label="Code" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code" wire:model="code" icon="bi-shield-check" />
                                <x-field label="New password" name="password" type="password" wire:model="password" autocomplete="new-password" icon="bi-key" />
                                <x-field label="Confirm new password" name="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" icon="bi-key" />
                                <button class="btn btn-primary btn-lg w-100" type="submit">Change password</button>
                            </form>
                            <p class="text-center small mt-3 mb-0"><button type="button" class="btn btn-link btn-sm p-0" wire:click="$set('codeSent', false)">Use a different email</button></p>
                        @endif
                        <p class="text-center text-muted small mt-4 mb-0"><a href="/Login">Back to sign in</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

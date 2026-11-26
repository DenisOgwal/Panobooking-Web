<div>
    <section class="section">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-6">
                    <div class="form-card">
                        @if ($registered)
                            <div class="success-panel">
                                <div class="check"><i class="bi bi-envelope-check" aria-hidden="true"></i></div>
                                <h1 class="h3">Thanks, {{ \Illuminate\Support\Str::of($name)->explode(' ')->first() }}!</h1>
                                <p class="text-muted">Your account for <strong>{{ $email }}</strong> has been created. We review new accounts quickly, and you'll be able to sign in once it's approved.</p>
                                <a class="btn btn-primary" href="/">Explore stays</a>
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/panaicon.png') }}" alt="" width="56" height="56" class="mb-3">
                                <h1 class="h3 mb-1">Create your account</h1>
                                <p class="text-muted mb-0">Book stays, cars, transfers and tours in one place.</p>
                            </div>
                            <form wire:submit="submit" novalidate>
                                <x-field label="Full name" name="name" wire:model.blur="name" autocomplete="name" icon="bi-person" />
                                <x-field label="Email" name="email" type="email" wire:model.blur="email" autocomplete="email" icon="bi-envelope" />
                                <div class="row g-0 column-gap-3">
                                    <div class="col-4 col-sm-3">
                                        <x-field label="Code" name="code" select wire:model="code">
                                            @foreach (['+256' => 'UG +256', '+254' => 'KE +254', '+255' => 'TZ +255', '+250' => 'RW +250', '+211' => 'SS +211', '+243' => 'CD +243', '+44' => 'UK +44', '+1' => 'US +1'] as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </x-field>
                                    </div>
                                    <div class="col"><x-field label="Phone number" name="phonenum" type="tel" wire:model.blur="phonenum" autocomplete="tel-national" placeholder="700 123456" /></div>
                                </div>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Password" name="password" type="password" wire:model="password" autocomplete="new-password" hint="At least 8 characters." /></div>
                                    <div class="col-sm"><x-field label="Confirm password" name="confirmpassword" type="password" wire:model.blur="confirmpassword" autocomplete="new-password" /></div>
                                </div>
                                <button class="btn btn-primary btn-lg w-100" type="submit">
                                    <span wire:loading.remove wire:target="submit">Create account</span>
                                    <span wire:loading wire:target="submit"><span class="spinner-border spinner-border-sm me-2"></span>Creating…</span>
                                </button>
                            </form>
                            <p class="text-center text-muted small mt-4 mb-0">Already have an account? <a href="/Login">Sign in</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

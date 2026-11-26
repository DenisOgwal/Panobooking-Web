<div>
    <x-page-hero title="Add an ambulance" subtitle="Help people reach care faster. We verify every ambulance before it is listed." :crumbs="[['List with us'], ['Ambulance']]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
                        @if ($submitted)
                            @include('livewire.partials.listing-submitted', ['what' => 'ambulance'])
                        @else
                            <form wire:submit="submit" novalidate>
                                <h2 class="h5 mb-3"><i class="bi bi-heart-pulse text-danger me-2"></i>The ambulance</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Number plate" name="carname" wire:model.blur="carname" placeholder="UAM 123A" /></div>
                                    <div class="col-sm">
                                        <x-field label="Level of care" name="carcategory" select wire:model="carcategory">
                                            @foreach (\App\Livewire\RegisterAmbulance::CATEGORIES as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
                                        </x-field>
                                    </div>
                                </div>
                                <x-field label="Vehicle" name="description" wire:model.blur="description" placeholder="e.g. Toyota Land Cruiser ambulance" />
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Operator / hospital" name="drivername" wire:model.blur="drivername" /></div>
                                    <div class="col-sm"><x-field label="Based in" name="Carlocation" wire:model.blur="Carlocation" icon="bi-geo-alt" /></div>
                                </div>
                                <x-field label="Payment method" name="paymentoption" wire:model.blur="paymentoption" placeholder="Cash, insurance, mobile money…" />

                                <h2 class="h5 mt-4 mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Contact &amp; account</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Dispatch phone" name="telphonenumber" type="tel" wire:model.blur="telphonenumber" icon="bi-telephone" /></div>
                                    <div class="col-sm"><x-field label="Email" name="email" type="email" wire:model.blur="email" icon="bi-envelope" /></div>
                                </div>
                                <x-field label="Driver app password" name="pswd" type="password" wire:model="pswd" autocomplete="new-password" hint="At least 8 characters." />
                                @include('livewire.partials.photo-upload')

                                <button class="btn btn-danger btn-lg w-100 mt-2" type="submit" wire:loading.attr="disabled" wire:target="formFile,submit">
                                    <span wire:loading.remove wire:target="submit">Submit ambulance</span>
                                    <span wire:loading wire:target="submit"><span class="spinner-border spinner-border-sm me-2"></span>Submitting…</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

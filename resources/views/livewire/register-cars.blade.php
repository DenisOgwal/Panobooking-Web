<div>
    <x-page-hero title="Register a rental car" subtitle="Earn from your vehicle. We review every car before it is listed." :crumbs="[['List with us'], ['Car']]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="form-card">
                        @if ($submitted)
                            @include('livewire.partials.listing-submitted', ['what' => 'car'])
                        @else
                            <form wire:submit="submit" novalidate>
                                <h2 class="h5 mb-3"><i class="bi bi-car-front text-primary me-2"></i>The car</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Number plate" name="carname" wire:model.blur="carname" placeholder="UAA 123A" /></div>
                                    <div class="col-sm"><x-field label="Make and model" name="CarSpecs" wire:model.blur="CarSpecs" placeholder="Toyota RAV4" /></div>
                                    <div class="col-sm">
                                        <x-field label="Category" name="carcategory" select wire:model="carcategory">
                                            @foreach (\App\Livewire\RegisterCars::CATEGORIES as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
                                        </x-field>
                                    </div>
                                </div>
                                <div class="row g-3 row-cols-2 row-cols-md-5 mb-3">
                                    <div class="col"><x-field label="Passengers" name="noofpassengers" type="number" min="1" wire:model.blur="noofpassengers" /></div>
                                    <div class="col"><x-field label="Doors" name="noofdoors" type="number" min="2" max="6" wire:model.blur="noofdoors" /></div>
                                    <div class="col"><x-field label="Luggage" name="laggagesoption" type="number" min="0" wire:model.blur="laggagesoption" /></div>
                                    <div class="col"><x-field label="Automatic" name="automaticoption" select wire:model="automaticoption"><option value="Yes">Yes</option><option value="No">No</option></x-field></div>
                                    <div class="col"><x-field label="Rating" name="carratings" type="number" min="1" max="5" step="0.5" wire:model.blur="carratings" /></div>
                                </div>
                                <x-field label="Description" name="description" textarea rows="3" wire:model.blur="description" placeholder="Condition, extras, with or without driver…" />

                                <h2 class="h5 mt-4 mb-3"><i class="bi bi-tag text-primary me-2"></i>Rates &amp; terms</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Kampala daily rate (UGX)" name="kampaladrive" type="number" min="1000" step="1000" wire:model.blur="kampaladrive" /></div>
                                    <div class="col-sm"><x-field label="Upcountry daily rate (UGX)" name="upcountrydrive" type="number" min="1000" step="1000" wire:model.blur="upcountrydrive" /></div>
                                    <div class="col-sm"><x-field label="Min. driver age" name="driversage" type="number" min="18" wire:model.blur="driversage" /></div>
                                </div>
                                <p class="small text-muted mt-n2">A {{ (int) (\App\Livewire\RegisterCars::COMMISSION * 100) }}% Panobooking service fee is added to the rates customers see.</p>
                                <x-field label="Payment method" name="paymentoption" wire:model.blur="paymentoption" placeholder="Cash, mobile money…" />

                                <h2 class="h5 mt-4 mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Owner &amp; account</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Company or owner name" name="Carcompany" wire:model.blur="Carcompany" /></div>
                                    <div class="col-sm"><x-field label="Car location" name="Carlocation" wire:model.blur="Carlocation" icon="bi-geo-alt" /></div>
                                </div>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Phone" name="telphonenumber" type="tel" wire:model.blur="telphonenumber" icon="bi-telephone" /></div>
                                    <div class="col-sm"><x-field label="Email" name="email" type="email" wire:model.blur="email" icon="bi-envelope" /></div>
                                    <div class="col-sm"><x-field label="Driver app password" name="pswd" type="password" wire:model="pswd" autocomplete="new-password" hint="At least 8 characters." /></div>
                                </div>
                                @include('livewire.partials.photo-upload')

                                <button class="btn btn-primary btn-lg w-100 mt-2" type="submit" wire:loading.attr="disabled" wire:target="formFile,submit">
                                    <span wire:loading.remove wire:target="submit">Submit car</span>
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

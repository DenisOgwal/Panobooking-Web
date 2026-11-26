<div>
    <x-page-hero title="List your property" subtitle="Reach travellers across Uganda and beyond. Listing is free; we review every property before it goes live." :crumbs="[['List with us'], ['Property']]" />
    <section class="section-sm">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="form-card">
                        @if ($submitted)
                            @include('livewire.partials.listing-submitted', ['what' => 'property'])
                        @else
                            <form wire:submit="submit" novalidate>
                                <h2 class="h5 mb-3"><i class="bi bi-building text-primary me-2"></i>The property</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-md-7"><x-field label="Property name" name="place" wire:model.blur="place" hint="Shown to guests exactly as typed." /></div>
                                    <div class="col-md">
                                        <x-field label="Type" name="placetype" select wire:model="placetype">
                                            @foreach (\App\Livewire\RegisterProperty::TYPES as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                                        </x-field>
                                    </div>
                                </div>
                                <x-field label="Short description" name="slogan" wire:model.blur="slogan" placeholder="e.g. Lakeside rooms with free breakfast and Wi-Fi" />
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Country" name="country" wire:model.blur="country" /></div>
                                    <div class="col-sm"><x-field label="Region" name="region" wire:model.blur="region" placeholder="e.g. Central" /></div>
                                    <div class="col-sm"><x-field label="City / town" name="city" wire:model.blur="city" /></div>
                                </div>
                                <x-field label="Address" name="exactlocation" wire:model.blur="exactlocation" icon="bi-geo-alt" placeholder="Plot, street, landmark" />

                                <h2 class="h5 mt-4 mb-3"><i class="bi bi-tag text-primary me-2"></i>Prices &amp; policies</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Lowest price per night (UGX)" name="leastprice" type="number" min="1000" step="1000" wire:model.blur="leastprice" /></div>
                                    <div class="col-sm"><x-field label="Star rating" name="ratings" type="number" min="1" max="5" step="0.5" wire:model.blur="ratings" /></div>
                                </div>
                                <x-field label="Price details" name="pricedescription" wire:model.blur="pricedescription" placeholder="e.g. Per room per night, breakfast included" />
                                <div class="row g-3 row-cols-2 row-cols-md-4 mb-3">
                                    @foreach ([['taxesinclusive', 'Taxes included'], ['paymentoption', 'Prepayment needed'], ['creditcard', 'Cards accepted'], ['bedandbreakfast', 'Breakfast available']] as [$field, $label])
                                        <div class="col">
                                            <x-field :label="$label" :name="$field" select wire:model="{{ $field }}"><option value="Yes">Yes</option><option value="No">No</option></x-field>
                                        </div>
                                    @endforeach
                                </div>

                                <h2 class="h5 mt-2 mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Contact &amp; photo</h2>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-field label="Phone" name="telephonenumber" type="tel" wire:model.blur="telephonenumber" icon="bi-telephone" /></div>
                                    <div class="col-sm"><x-field label="Booking email" name="email" type="email" wire:model.blur="email" icon="bi-envelope" hint="Booking requests are sent here." /></div>
                                </div>
                                @include('livewire.partials.photo-upload')

                                <button class="btn btn-primary btn-lg w-100 mt-2" type="submit" wire:loading.attr="disabled" wire:target="formFile,submit">
                                    <span wire:loading.remove wire:target="submit">Submit property</span>
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

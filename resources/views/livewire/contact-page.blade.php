<div>
    <x-page-hero title="Contact us" subtitle="Send us a message, email or call. We reply quickly." :crumbs="[['Help', '/HelpCenter'], ['Contact']]" />
    <section class="section">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <h2 class="h4">Send a message</h2>
                        <p class="text-muted">Questions about a booking, listing your property, or anything else.</p>
                        <form wire:submit="sendmessage" novalidate>
                            <div class="row g-0 column-gap-3">
                                <div class="col-sm"><x-field label="Your name" name="inputname" wire:model.blur="inputname" autocomplete="name" icon="bi-person" /></div>
                                <div class="col-sm"><x-field label="Email" name="email" type="email" wire:model.blur="email" autocomplete="email" icon="bi-envelope" /></div>
                            </div>
                            <x-field label="Message" name="contactinfo" textarea rows="5" wire:model.blur="contactinfo" />
                            <button class="btn btn-primary btn-lg" type="submit">
                                <span wire:loading.remove wire:target="sendmessage"><i class="bi bi-send me-2"></i>Send message</span>
                                <span wire:loading wire:target="sendmessage"><span class="spinner-border spinner-border-sm me-2"></span>Sending…</span>
                            </button>
                        </form>
                    </div>
                </div>
                <aside class="col-lg-5">
                    <div class="booking-box">
                        <h2 class="h5 mb-3">Our office</h2>
                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                            <li class="d-flex gap-3"><i class="bi bi-geo-alt text-primary fs-5"></i>
                                <span>UMF House, 3rd Floor, Plot 160, Sir Apollo Kaggwa Road, Kampala<br><small class="text-muted">1 km off the Northern Bypass, Bwaise flyover</small></span></li>
                            <li class="d-flex gap-3"><i class="bi bi-telephone text-primary fs-5"></i>
                                <span><a href="tel:+256741822377">+256 741 822377</a><br><a href="tel:+256760132023">+256 760 132023</a></span></li>
                            <li class="d-flex gap-3"><i class="bi bi-whatsapp text-success fs-5"></i>
                                <span><a href="https://wa.me/256741822377" rel="noopener" target="_blank">WhatsApp +256 741 822377</a></span></li>
                            <li class="d-flex gap-3"><i class="bi bi-envelope text-primary fs-5"></i>
                                <span><a href="mailto:info@panobooking.email">info@panobooking.email</a></span></li>
                            <li class="d-flex gap-3"><i class="bi bi-clock text-primary fs-5"></i>
                                <span>Office: Monday–Friday, 8am–5pm<br><small class="text-muted">Online booking and support 24/7</small></span></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

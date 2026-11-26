<div>
    <x-page-hero :title="$title" :subtitle="$subtitle" :crumbs="[['Help', '/HelpCenter'], [$title]]" />
    <section class="section">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    <article class="form-card prose mw-100">
                        @include('livewire.content.' . $partial)
                    </article>
                </div>
                <aside class="col-lg-4">
                    <div class="booking-box">
                        <h2 class="h6 text-uppercase text-muted">Help &amp; policies</h2>
                        <nav class="d-flex flex-column gap-1 mt-2">
                            @foreach ([['/HelpCenter', 'bi-life-preserver', 'Help centre'], ['/Faqs', 'bi-question-circle', 'FAQs'],
                                       ['/privacypolicy', 'bi-shield-lock', 'Privacy policy'], ['/TermsAndConditions', 'bi-file-text', 'Terms & conditions'],
                                       ['/contactus', 'bi-envelope', 'Contact us']] as [$href, $i, $label])
                                <a @class(['btn btn-ghost text-start btn-icon', 'active fw-bold text-primary' => request()->is(ltrim($href, '/'))]) href="{{ $href }}">
                                    <i class="bi {{ $i }}"></i>{{ $label }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

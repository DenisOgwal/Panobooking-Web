@php use App\Support\Media; use Illuminate\Support\Carbon; @endphp
<div>
    <x-page-hero :title="$post->BlogTitle" :subtitle="$post->BlogShortDescription" :crumbs="[['Blog', '/blog'], [\Illuminate\Support\Str::limit($post->BlogTitle, 40)]]">
        <div class="mt-2 small text-white-50"><i class="bi bi-calendar3 me-1"></i>{{ $post->Date ? Carbon::parse($post->Date)->format('j F Y') : '' }}</div>
    </x-page-hero>

    <section class="section-sm">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    <article class="form-card">
                        <img class="w-100 rounded-3 mb-4 object-fit-cover" height="360" src="{{ Media::image('blog', $post) }}" data-fallback="{{ Media::placeholder() }}" alt="">
                        <div class="prose mw-100">{!! nl2br(e($post->Description)) !!}</div>
                    </article>

                    <div class="detail-section mt-4" id="comments">
                        <h2>{{ $approvedComments->count() }} {{ \Illuminate\Support\Str::plural('comment', $approvedComments->count()) }}</h2>
                        @foreach ($approvedComments as $c)
                            <div class="d-flex gap-3 py-3 border-bottom">
                                <span class="testimonial p-0 border-0 bg-transparent"><span class="avatar">{{ mb_substr($c->NameOfCommenter, 0, 1) }}</span></span>
                                <div>
                                    <strong>{{ $c->NameOfCommenter }}</strong>
                                    <small class="text-muted ms-2">{{ $c->CommentDate ? Carbon::parse($c->CommentDate)->format('j M Y') : $c->created_at?->format('j M Y') }}</small>
                                    <p class="mb-0 mt-1">{{ $c->Comment }}</p>
                                </div>
                            </div>
                        @endforeach

                        <h3 class="h5 mt-4">Leave a comment</h3>
                        <form wire:submit="comment" novalidate>
                            <div class="row g-0 column-gap-3">
                                <div class="col-sm"><x-field label="Name" name="inputname" wire:model.blur="inputname" autocomplete="name" /></div>
                                <div class="col-sm"><x-field label="Email (not published)" name="email" type="email" wire:model.blur="email" autocomplete="email" /></div>
                            </div>
                            <x-field label="Comment" name="comments" textarea rows="4" wire:model.blur="comments" />
                            <button class="btn btn-primary" type="submit">
                                <span wire:loading.remove wire:target="comment">Post comment</span>
                                <span wire:loading wire:target="comment"><span class="spinner-border spinner-border-sm me-2"></span>Posting…</span>
                            </button>
                        </form>
                    </div>
                </div>
                <aside class="col-lg-4">
                    @if ($more->isNotEmpty())
                        <div class="booking-box">
                            <h2 class="h6 text-uppercase text-muted mb-3">More guides</h2>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($more as $m)
                                    <a class="d-flex gap-3 text-body" href="{{ route('BlogDetail', ['blogslug' => $m->id]) }}">
                                        <img class="rounded-3 flex-shrink-0 object-fit-cover" width="84" height="64" src="{{ Media::image('blog', $m) }}" data-fallback="{{ Media::placeholder() }}" alt="" loading="lazy">
                                        <span class="fw-semibold small">{{ $m->BlogTitle }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</div>

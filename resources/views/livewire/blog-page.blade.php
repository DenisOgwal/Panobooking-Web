@php use App\Support\Media; use Illuminate\Support\Carbon; @endphp
<div>
    <x-page-hero title="Travel guides" subtitle="Ideas, tips and itineraries for exploring Uganda." :crumbs="[['Blog']]" />
    <section class="section">
        <div class="container-xl">
            @if ($posts->isEmpty())
                <x-empty-state icon="bi-journal-text" title="No articles yet" />
            @else
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($posts as $post)
                        <div class="col" wire:key="post-{{ $post->id }}">
                            <article class="blog-card">
                                <div class="listing-media">
                                    <img src="{{ Media::image('blog', $post) }}" data-fallback="{{ Media::placeholder() }}" alt="" loading="lazy">
                                </div>
                                <div class="listing-body">
                                    <div class="listing-meta">
                                        <i class="bi bi-calendar3"></i>{{ $post->Date ? Carbon::parse($post->Date)->format('j M Y') : '' }}
                                        <span aria-hidden="true">·</span><i class="bi bi-chat"></i>{{ $post->approved_comments_count }}
                                    </div>
                                    <h2 class="listing-title"><a href="{{ route('BlogDetail', ['blogslug' => $post->id]) }}">{{ $post->BlogTitle }}</a></h2>
                                    <p class="excerpt mb-0">{{ $post->BlogShortDescription }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
</div>

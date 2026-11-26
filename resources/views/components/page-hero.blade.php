{{-- Compact hero for inner pages. $crumbs: [['Label', '/url'], ['Current']] --}}
@props(['title', 'subtitle' => null, 'crumbs' => []])
<section {{ $attributes->class('page-hero') }}>
    <div class="container-xl">
        @if ($crumbs)
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    @foreach ($crumbs as $crumb)
                        @if (isset($crumb[1]) && ! $loop->last)
                            <li class="breadcrumb-item"><a href="{{ $crumb[1] }}">{{ $crumb[0] }}</a></li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">{{ $crumb[0] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif
        <h1>{{ $title }}</h1>
        @if ($subtitle)<p>{{ $subtitle }}</p>@endif
        {{ $slot }}
    </div>
</section>

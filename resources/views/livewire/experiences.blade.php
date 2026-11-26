{{-- Tourism sites or events list. Expects $items (paginator), $kind ('site'|'event'), $title, $subtitle --}}
@php use App\Support\Media; use Illuminate\Support\Carbon; use Illuminate\Support\Str; $isEvent = $kind === 'event'; @endphp
<div>
    <x-page-hero :title="$title" :subtitle="$subtitle" :crumbs="[[$title]]" />
    <section class="section">
        <div class="container-xl">
            @if ($items->isEmpty())
                <x-empty-state :icon="$isEvent ? 'bi-calendar-event' : 'bi-camera'" :title="$isEvent ? 'No upcoming events' : 'No tourism sites yet'" />
            @else
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    @foreach ($items as $item)
                        <div class="col" wire:key="{{ $kind }}-{{ $item->ID }}">
                            <x-listing-card
                                :href="$isEvent ? route('EventsDetails', ['searchslug' => $item->ID]) : route('TourismSiteDetails', ['searchslug' => $item->ID])"
                                :image="Media::image($kind, $item)"
                                :title="$isEvent ? $item->EventName : $item->SiteName"
                                :location="collect([$item->City, $item->Country])->filter()->implode(', ')"
                                :badge="$isEvent && $item->StartDate ? Carbon::parse($item->StartDate)->format('j M') : null"
                                badge-accent
                                :cta="$isEvent ? 'Details' : 'Explore'">
                                {{ Str::limit($item->ShortDescription, 90) }}
                            </x-listing-card>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $items->links() }}</div>
            @endif
        </div>
    </section>
</div>

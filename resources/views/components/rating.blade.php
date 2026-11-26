@props(['value' => null, 'chip' => false])
@if (is_numeric($value) && $value > 0)
    <span {{ $attributes->class(['rating', 'rating-chip' => $chip]) }} title="Rated {{ number_format($value, 1) }} out of 5">
        <i class="bi bi-star-fill" aria-hidden="true"></i>{{ number_format($value, 1) }}
    </span>
@endif

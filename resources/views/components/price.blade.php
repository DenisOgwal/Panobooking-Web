{{-- Price in the visitor's currency. $amount is always UGX. --}}
@props(['amount', 'unit' => null, 'prefix' => null, 'size' => null])
@php
    $currency = app(\App\Support\Currency::class);
    $code = $currency->code();
    $value = number_format($currency->convert($amount), 0);
@endphp
<div {{ $attributes->class(['price', 'price-lg' => $size === 'lg']) }}>
    @if ($prefix)<span class="unit">{{ $prefix }}</span>@endif
    <span class="currency">{{ $code }}</span><span class="amount">{{ $value }}</span>
    @if ($unit)<span class="unit">{{ $unit }}</span>@endif
</div>

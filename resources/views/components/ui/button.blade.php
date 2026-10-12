@props([
    'variant' => 'primary',
    'type' => 'button',
    'id' => null,
    'href' => null
])

@php
    $baseClasses = 'btn';
    $variantClasses = 'btn-' . $variant;
@endphp

@if($href)
    <a href="{{ $href }}" @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
        {{ $slot }}
    </button>
@endif

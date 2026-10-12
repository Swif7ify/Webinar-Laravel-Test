@props([
    'variant' => 'neutral', 
    'label'   => null
])

<span {{ $attributes->merge(['class' => "badge badge--{$variant}"]) }}>
    {{ $label ?? $slot }}
</span>

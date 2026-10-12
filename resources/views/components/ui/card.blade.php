@props([
    'title' => null
])

<div {{ $attributes->merge(['class' => 'app-card']) }}>
    @if($title)
        <div class="app-card__header">
            <h3 class="app-card__title">{{ $title }}</h3>
        </div>
    @endif
    <div class="app-card__body">
        {{ $slot }}
    </div>
</div>

@props([
    'title',
    'value',
    'color' => 'primary', 
    'trendText' => null,
    'trendColor' => 'success',
    'trendIcon' => 'up',
    'href' => '#'
])

@php
    $trendClass = match($trendColor) {
        'success' => 'text-success',
        'warning' => 'text-warning',
        'error' => 'text-error',
        default => 'text-muted',
    };
@endphp

<div class="stat-card color-{{ $color }}">
    <div class="stat-card-header">
        <div class="stat-card-icon-wrapper">
            {{ $icon }}
        </div>
        <span class="stat-card-title">{{ $title }}</span>
    </div>
    
    <div class="stat-card-body">
        <div class="stat-card-value">{{ $value }}</div>
    </div>
    
    @if($trendText)
    <div class="stat-card-footer {{ $trendClass }}">
        @if($trendIcon === 'up')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="stat-trend-icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
            </svg>
        @endif
        <span class="stat-trend-text">{{ $trendText }}</span>
    </div>
    @endif
</div>

@props([
    'classCode',
    'title',
    'schedule',
    'teacherName' => null,
    'link' => '#'
])

<div {{ $attributes->merge(['class' => 'class-card']) }}>
    <div class="class-card-header">
        <div class="class-card-info">
            <div class="class-card-info-top">
                <p class="class-card-code">Classcode: {{ $classCode }}</p>
                <h3 class="class-card-title">{{ $title }}</h3>
            </div>
            <p class="class-card-schedule">{{ $schedule }}</p>
        </div>
        <div class="class-card-logo">
            <img src="{{ asset('images/OneDev-Logo.png') }}" alt="Logo">
        </div>
    </div>
    <div class="class-card-footer">
        @if($teacherName)
        <div class="class-card-teacher">
            <div class="class-card-avatar">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <span class="class-card-teacher-name">{{ $teacherName }}</span>
        </div>
        @endif
        <a href="{{ $link }}" class="class-card-btn">Enter &gt;</a>
    </div>
</div>
